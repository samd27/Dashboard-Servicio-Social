<?php
#Este es el guardia que verifica los niveles 0, 1, 2
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (! $request->user()) {
            return redirect('/login');
        }

        if ($request->user()->nivel_usuario > $role) {
            abort(403);
        }

        return $next($request);
    }
}
