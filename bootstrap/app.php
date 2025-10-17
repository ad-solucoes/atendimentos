<?php

declare(strict_types = 1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web([
            \App\Http\Middleware\RegistrarVisita::class,
        ]);

        $middleware->alias([
            'role'               => Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin'              => App\Http\Middleware\AdminMiddleware::class,
            'verified'           => App\Http\Middleware\EnsureEmailIsVerified::class,
            'password.change'    => App\Http\Middleware\MustChangePassword::class,
        ]);

        $middleware->redirectGuestsTo('/admin/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
