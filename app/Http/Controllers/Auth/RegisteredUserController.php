<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDACIÓN
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nombre_cuenta' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:13', 'unique:cuentas'], // Asegura RFC único en 'cuentas'
            'telefono' => ['required', 'string', 'max:10'],
            'email_2_opcional' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        // 2. LÓGICA DE TRANSACCIÓN (RF05-C2)
        $user = DB::transaction(function () use ($request) {
            
            // a. Crea la Cuenta primero
            $cuenta = Cuenta::create([
                'nombre_cuenta' => $request->nombre_cuenta,
                'rfc' => $request->rfc,
                'telefono' => $request->telefono,
                'email_2_opcional' => $request->email_2_opcional,
            ]);

            // b. Crea el Usuario y asígnalo a la Cuenta recién creada
            $usuario = $cuenta->users()->create([
                'name' => $request->email, 
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_key' => Str::random(32),
                'nivel_usuario' => 1,
            ]);

            return $usuario;
        });

        // 3. El resto del código de Breeze maneja el login
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}