<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless(
            in_array((string) $request->session()->get('user_type'), $roles, true),
            Response::HTTP_FORBIDDEN,
            'Acción no permitida.'
        );

        return $next($request);
    }
}
