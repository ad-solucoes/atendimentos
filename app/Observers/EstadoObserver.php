<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Log;
use App\Models\Estado;

class EstadoObserver
{
    public $afterCommit = true;

    public function creating($estado)
    {
        if (auth()->check()) {
            $estado->created_user_id = auth()->user()->id;
        }
    }

    public function created(Estado $estado): void
    {
        if ($estado && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'estados',
                'event'      => 'created',
                'table_id'   => $estado->estado_id,
                'table_type' => Estado::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de estado ' . $estado->estado_nome . ', com ID #' . formatoId($estado->estado_id, 6) . ' com sucesso');
        }
    }

    public function updating($estado)
    {
        if (auth()->check()) {
            $estado->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Estado $estado): void
    {
        if ($estado && auth()->check()) {
            $changes = $estado->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'estados',
                        'event'      => 'updated',
                        'table_id'   => $estado->estado_id,
                        'table_type' => Estado::class,
                        'column'     => $key,
                        'old_value'  => $estado->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de estado ' . $estado->estado_nome . ', com ID #' . formatoId($estado->estado_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Estado $estado): void
    {
        if ($estado && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'estados',
                'event'      => 'deleted',
                'table_id'   => $estado->estado_id,
                'table_type' => Estado::class,
                'data'       => $estado->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de estado ' . $estado->estado_nome . ', com ID #' . formatoId($estado->estado_id, 6) . ' com sucesso');
        }
    }
}
