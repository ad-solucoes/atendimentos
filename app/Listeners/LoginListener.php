<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Models\Access;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class LoginListener
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected Request $request
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        // Registra o login apenas na tabela audit_logins para controle de acessos
        Access::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'action'     => 'login_attempt',
            'success'    => true,
            'metadata'   => json_encode([
                'session_id' => $this->request->session()->getId(),
                'timestamp'  => now(),
            ]),
            'created_at' => now(),
        ]);
    }
}
