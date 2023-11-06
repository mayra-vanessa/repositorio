<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PacienteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->tipo_usuario == 3) {
            return $next($request);
        }

        return redirect()->route('Login'); // Redireccionar a la página de inicio si el usuario no es un administrador
    }
}
