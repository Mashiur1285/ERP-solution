<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfMissingPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (! $request->user()?->checkPermissionTo($permission)) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
