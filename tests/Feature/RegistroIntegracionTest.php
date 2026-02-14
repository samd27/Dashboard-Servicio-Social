<?php

namespace Tests\Feature;

use App\Mail\ConfirmacionRegistroMail;
use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistroIntegracionTest extends TestCase
{
    use RefreshDatabase; // Borra y crea la BD para cada prueba

    /**
     * Prueba el flujo completo de registro (Happy Path).
     * RF04, RF05, RF06
     */
    public function test_flujo_completo_registro_exitoso(): void
    {
        // 1. PREPARACIÓN
        // Fingimos el envío de correos para no enviarlos de verdad
        Mail::fake();

        // Datos del formulario (Simulando lo que escribe el usuario)
        $datos = [
            'nombre_cuenta' => 'Empresa Test S.A.',
            'rfc' => 'TEST010101T01',
            'telefono' => '5512345678',
            'email_2_opcional' => 'contacto@empresa.com',
            'email' => 'admin@empresa.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // 2. ACCIÓN (Simular el clic en "Registrar")
        $response = $this->post('/register', $datos);

        // 3. VERIFICACIONES (ASSERTS)

        // A) Verificar Base de Datos (RF05)
        $this->assertDatabaseHas('cuentas', [
            'nombre_cuenta' => 'Empresa Test S.A.',
            'rfc' => 'TEST010101T01',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@empresa.com',
            'nivel_usuario' => 1,
        ]);

        // B) Verificar Redirección y Mensaje Web (RF06 - Modal)
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('status', '¡Registro exitoso! Hemos enviado un correo con tus datos de acceso y documentación adjunta. Por favor, inicia sesión con tu email y contraseña.');

        // C) Verificar Envío de Correo y Adjuntos (RF06 - Email)
        // Verificamos que se haya enviado el Mailable correcto al usuario correcto
        Mail::assertSent(ConfirmacionRegistroMail::class, function ($mail) use ($datos) {
            
            // Verifica el destinatario
            $tieneDestinatarioCorrecto = $mail->hasTo($datos['email']);
            
            // Verifica que tenga 2 adjuntos (los PDFs)
            // Laravel 11 maneja los adjuntos en el método attachments(), 
            // así que verificamos que ese array tenga 2 elementos.
            $tieneAdjuntos = count($mail->attachments()) === 2;

            return $tieneDestinatarioCorrecto && $tieneAdjuntos;
        });
    }

    /**
     * Prueba de validación (Caso Borde).
     * Verifica que no se pueda registrar un RFC duplicado.
     */
    public function test_no_permite_rfc_duplicado(): void
    {
        // Crear una cuenta previa
        Cuenta::create([
            'nombre_cuenta' => 'Empresa Original',
            'rfc' => 'DUPLICADO123',
            'telefono' => '1111111111',
        ]);

        // Intentar registrar otra con el mismo RFC
        $response = $this->post('/register', [
            'nombre_cuenta' => 'Empresa Copia',
            'rfc' => 'DUPLICADO123', // <-- RFC Repetido
            'telefono' => '2222222222',
            'email' => 'otro@correo.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Debe fallar y regresar error en 'rfc'
        $response->assertSessionHasErrors('rfc');
        
        // Verificar que NO se creó la segunda cuenta
        $this->assertDatabaseMissing('cuentas', [
            'nombre_cuenta' => 'Empresa Copia',
        ]);
    }
}