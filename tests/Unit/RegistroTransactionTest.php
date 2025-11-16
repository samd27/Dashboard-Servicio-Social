<?php

namespace Tests\Unit;

use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase; // ¡Importante!
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegistroTransactionTest extends TestCase
{
    use RefreshDatabase; // Esto limpia la base de datos después de cada prueba

    /**
     * Prueba el "Happy Path" (RF05-C2).
     * Verifica que la transacción crea la Cuenta y el Usuario correctamente.
     */
    public function test_registro_exitoso_crea_cuenta_y_usuario(): void
    {
        // 1. Define los datos de prueba
        $datosRegistro = [
            'nombre_cuenta' => 'Cuenta de Prueba',
            'rfc' => 'XAXX010101000',
            'telefono' => '2221234567',
            'email_2_opcional' => null,
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // 2. Ejecuta la lógica del controlador (la transacción)
        $user = DB::transaction(function () use ($datosRegistro) {
            $cuenta = Cuenta::create([
                'nombre_cuenta' => $datosRegistro['nombre_cuenta'],
                'rfc' => $datosRegistro['rfc'],
                'telefono' => $datosRegistro['telefono'],
                'email_2_opcional' => $datosRegistro['email_2_opcional'],
            ]);

            $usuario = $cuenta->users()->create([
                'name' => $datosRegistro['email'],
                'email' => $datosRegistro['email'],
                'password' => Hash::make($datosRegistro['password']),
                'api_key' => Str::random(32),
                'nivel_usuario' => 1,
            ]);

            return $usuario;
        });

        // 3. Verifica los resultados
        $this->assertDatabaseHas('cuentas', [
            'rfc' => 'XAXX010101000',
        ]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Verifica que la relación se haya creado
        $this->assertEquals(1, $user->cuenta_id);
    }

    /**
     * Prueba el "Rollback" (RF05-C2).
     * Verifica que si el Usuario falla, la Cuenta no se crea.
     */
    public function test_registro_falla_si_usuario_falla_y_hace_rollback(): void
    {
        // 1. Verifica que las tablas estén vacías
        $this->assertDatabaseCount('cuentas', 0);
        $this->assertDatabaseCount('users', 0);

        try {
            // 2. Ejecuta la transacción forzando un error
            // (Enviaremos 'email' como null, lo cual fallará la BD)
            DB::transaction(function () {
                $cuenta = Cuenta::create([
                    'nombre_cuenta' => 'Cuenta Fallida',
                    'rfc' => 'XAXX010101001',
                    'telefono' => '2221234568',
                ]);

                // Forzamos el error aquí (email es NOT NULL)
                $cuenta->users()->create([
                    'name' => null, 
                    'email' => null, // <-- Error
                    'password' => 'password123',
                    'api_key' => Str::random(32),
                    'nivel_usuario' => 1,
                ]);
            });
        } catch (\Exception $e) {
            // Capturamos la excepción (esperada)
        }

        // 3. Verifica que NADA se haya creado (Rollback)
        $this->assertDatabaseCount('cuentas', 0);
        $this->assertDatabaseCount('users', 0);
    }
}