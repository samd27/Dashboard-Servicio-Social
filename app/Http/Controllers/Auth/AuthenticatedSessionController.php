<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Importante para ver el código
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validar credenciales y vigencia (LoginRequest)
        $request->authenticate();

        // 2. Regenerar sesión por seguridad
        $request->session()->regenerate();

        // --- INICIO LÓGICA 2FA (ALAN) ---
        $user = Auth::user();

        // Generar código de 6 dígitos
        $code = rand(100000, 999999);

        // Guardar código y expiración (10 mins) en la BD
        $user->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        // LOG PARA PRUEBAS: Aquí verás el código en storage/logs/laravel.log
        Log::info("Código de verificación para {$user->email}: {$code}");

        // Redirigir a la pantalla de introducir código
        return redirect()->route('verify.2fa.index');
        // --- FIN LÓGICA 2FA ---
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
