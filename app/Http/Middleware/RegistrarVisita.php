<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegistrarVisita
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Registra apenas páginas do site público (não admin)
        if (! $request->is('admin/*') && $request->method() === 'GET') {
            DB::table('visitas')->insert([
                'ip' => $request->ip(),
                'pagina' => $request->path(),
                'user_agent' => substr($request->header('User-Agent'), 0, 255),
                'visitado_em' => now(),
            ]);
        }

        return $next($request);
    }
}
