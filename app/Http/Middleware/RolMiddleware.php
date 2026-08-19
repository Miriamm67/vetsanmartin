<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, string $rol)
    {
        if (!auth()->check() || auth()->user()->rol !== $rol || auth()->user()->estado !== 'activo')
            abort(403, 'Acceso no autorizado');
        return $next($request);
    }
}