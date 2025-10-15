<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Specify the redirect route and message for unverified users.
     */
    public function handle(Request $request, Closure $next, string $redirectToRoute = null): Response
    {
        if (! $request->user() ||
            ($request->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message'               => 'Seu email precisa ser verificado antes de acessar o sistema.',
                    'verification_required' => true,
                ], 409);
            }

            return $request->user()
                ? Redirect::guest(URL::route($redirectToRoute ?: 'admin.verification.notice'))
                : Redirect::guest(route('admin.login'));
        }

        return $next($request);
    }
}
