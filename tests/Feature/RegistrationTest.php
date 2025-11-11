<?php

test('un usuario puede registrar una nueva cuenta y usuario', function () {
    
    // Simula el envío de datos al endpoint de registro
    $response = $this->post('/register', [
        'name' => 'Usuario de Prueba',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        
        // Tus campos (RF04)
        'nombre_cuenta' => 'Cuenta de Prueba SA',
        'rfc' => 'XAXX010101000',
        'telefono' => '2221234567',
        'email_2_opcional' => null,
    ]);

    // 1. ¿Inició sesión?
    $this->assertAuthenticated();
    
    // 2. ¿Se creó la cuenta?
    $this->assertDatabaseHas('cuentas', [
        'rfc' => 'XAXX010101000',
        'nombre_cuenta' => 'Cuenta de Prueba SA',
    ]);

    // 3. ¿Se creó el usuario?
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'nivel_usuario' => 1,
    ]);

    // 4. ¿Nos redirigió al dashboard?
    $response->assertRedirect(route('dashboard', absolute: false));
});