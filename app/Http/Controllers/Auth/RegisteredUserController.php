<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\User;
use App\Http\Requests\Auth\RegistroRequest; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

    public function store(RegistroRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            
            $cuenta = Cuenta::create([
                'nombre_cuenta' => $request->nombre_cuenta,
                'rfc' => $request->rfc,
                'telefono' => $request->telefono,
                'email_2_opcional' => $request->email_2_opcional,
            ]);

            $usuario = $cuenta->users()->create([
                'name' => $request->email, // 'name' se toma del 'email'
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_key' => Str::random(32),
                'nivel_usuario' => 1,
            ]);

            return $usuario;
        });

        event(new Registered($user));
        //Auth::login($user); Ya no se hace login automático tras el registro

       return redirect()->route('login')->with('status', '¡Registro exitoso! Hemos enviado los detalles a tu correo.');
    }
}