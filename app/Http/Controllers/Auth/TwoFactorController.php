<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.two-factor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => ['required', 'integer'],
        ]);

        $user = Auth::user();

        if ($request->two_factor_code == $user->two_factor_code &&
            now()->lessThan($user->two_factor_expires_at)) {

            $user->forceFill([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ])->save();

            $request->session()->put('2fa_verified', true);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['two_factor_code' => 'El código es inválido o ha expirado.']);
    }
}
