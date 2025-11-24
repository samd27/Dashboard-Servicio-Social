<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\User;
use App\Http\Requests\Auth\RegistroRequest; // Importamos tu Form Request
use App\Mail\ConfirmacionRegistroMail;      // Importamos tu Mailable
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;        // Importamos la Facade Mail
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
        // Iniciamos la transacción para asegurar consistencia
        $user = DB::transaction(function () use ($request) {
            
            // 1. Crear la Cuenta
            $cuenta = Cuenta::create([
                'nombre_cuenta' => $request->nombre_cuenta,
                'rfc' => $request->rfc,
                'telefono' => $request->telefono,
                'email_2_opcional' => $request->email_2_opcional,
            ]);

            // 2. Crear el Usuario enlazado a la cuenta
            $usuario = $cuenta->users()->create([
                'name' => $request->email, // RF05: El nombre es el email
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_key' => Str::random(32), // RF05: Generar API Key
                'nivel_usuario' => 1,         // RF05: Nivel por defecto
            ]);

            // Es importante devolver el usuario creado dentro de la transacción
            // para poder usarlo fuera de ella (para el evento y el correo).
            // Eloquent ya cargó la relación 'cuenta' implícitamente al crearlo así,
            // pero si necesitamos acceder a $cuenta después, podemos usar $usuario->cuenta
            return $usuario;
        });

        // Evento de registro estándar de Laravel
        event(new Registered($user));

        // RF06: Enviar correo de confirmación con PDFs
        // Pasamos el usuario y su cuenta (accesible vía relación o cargándola)
        // Nota: $user->cuenta funciona si definiste la relación en el modelo User
        Mail::to($user->email)->send(new ConfirmacionRegistroMail($user, $user->cuenta));

        // CAMBIO: No hacemos login automático.
        // Auth::login($user);

        // RF06: Redirigir al login con mensaje para el Modal
        return redirect()->route('login')->with('status', '¡Registro exitoso! Hemos enviado los detalles y manuales a tu correo.');
    }
}