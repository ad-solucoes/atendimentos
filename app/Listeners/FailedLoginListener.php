<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Models\Access;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class FailedLoginListener
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
    public function handle(Failed $event): void
    {
        // Para falhas de login, vamos registrar diretamente na tabela audit_logins
        // pois pode não ter um usuário válido

        Access::create([
            'user_id'    => $event->user ? $event->user->id : null,
            'email'      => $this->request->input('email', 'unknown'),
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'action'     => 'login_attempt',
            'success'    => false,
            'metadata'   => json_encode([
                'browser'              => browser(),
                'timestamp'            => now(),
                'credentials_provided' => $this->request->has('email'),
            ]),
            'created_at' => now(),
        ]);
    }
}
