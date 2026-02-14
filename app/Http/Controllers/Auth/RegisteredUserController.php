<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\User;
use App\Http\Requests\Auth\RegistroRequest;
use App\Mail\ConfirmacionRegistroMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        // 1. TRANSACCIÓN: Crear Cuenta y Usuario asegurando consistencia
        $user = DB::transaction(function () use ($request) {
            
            // A) Crear la Cuenta con los datos del formulario
            $cuenta = Cuenta::create([
                'nombre_cuenta' => $request->nombre_cuenta,
                'rfc' => $request->rfc,
                'telefono' => $request->telefono,
                'email_2_opcional' => $request->email_2_opcional,
            ]);

            // B) Crear el Usuario enlazado a la cuenta
                       $usuario = $cuenta->users()->create([

                'name' => $request->email, // RF05: El nombre es el email

                'email' => $request->email,

                'password' => Hash::make($request->password),

                'api_key' => Str::random(32), // RF05: Generar API Key

                'nivel_usuario' => 1,         // RF05: Nivel por defecto

            ]);

            // Devolvemos el usuario para usarlo fuera de la transacción
            return $usuario;
        });

        // 2. Evento de registro estándar
        event(new Registered($user));

        // 3. Enviar correo (Con try-catch para evitar errores si no hay internet)
        try {
            Mail::to($user->email)->send(new ConfirmacionRegistroMail($user, $user->cuenta));
        } catch (\Exception $e) {
            // Si falla el correo, continuamos sin error
        }

        // 4. REDIRECCIÓN: Mandar al Login con mensaje de éxito
        return redirect()->route('login')->with('status', '¡Registro exitoso! Hemos enviado un correo con tus datos de acceso y documentación adjunta. Por favor, inicia sesión con tu email y contraseña.');
    }
}