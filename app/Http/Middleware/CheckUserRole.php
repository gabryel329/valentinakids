<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRole
{
    public function handle($request, Closure $next, $role)
    {
        if (auth()->check() && auth()->user()->tipo === $role) {
            return $next($request);
        }

        return redirect('/lista'); // Redirecionar para /lista por padrão
    }
}

