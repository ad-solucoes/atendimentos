<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->mustChangePassword()) {
            // Allow access to password change routes and logout
            $allowedRoutes = [
                'password.change',
                'password.update',
                'logout',
                'verification.*',
            ];

            $currentRoute = $request->route()?->getName();

            foreach ($allowedRoutes as $route) {
                if (str_contains($route, '*') ? str_starts_with($currentRoute, str_replace('*', '', $route)) : $currentRoute === $route) {
                    return $next($request);
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'message'              => 'Você deve alterar sua senha antes de continuar.',
                    'must_change_password' => true,
                    'redirect_url'         => route('admin.password.change'),
                ], 409);
            }

            return redirect()->route('admin.password.change')
                ->with('warning', 'Por segurança, você deve alterar sua senha temporária antes de continuar.');
        }

        return $next($request);
    }
}
