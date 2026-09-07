<?php

use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\RedirectIfMissingPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectUsersTo('/home');

        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'permission.redirect' => RedirectIfMissingPermission::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'suppliers/store',
            'sales/store',
            'sales/payment/store/*',
            'lifts/store',
            'api/product-catalog/quick-store',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
