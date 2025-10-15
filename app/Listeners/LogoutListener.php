<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Models\Access;
use App\Models\User;
use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

class LogoutListener
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
    public function handle(Logout $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if (! $user) {
            return;
        }

        // Registra o logout na tabela audit_logins
        Access::create([
            'user_id'    => $user->id,
            'email'      => $user->email,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'action'     => 'logout',
            'success'    => true,
            'metadata'   => json_encode([
                'timestamp' => now(),
            ]),
            'created_at' => now(),
        ]);
    }
}
