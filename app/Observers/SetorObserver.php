<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Log;
use App\Models\Setor;

class SetorObserver
{
    public $afterCommit = true;

    public function creating($setor)
    {
        if (auth()->check()) {
            $setor->created_user_id = auth()->user()->id;
        }
    }

    public function created(Setor $setor): void
    {
        if ($setor && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'setores',
                'event'      => 'created',
                'table_id'   => $setor->setor_id,
                'table_type' => Setor::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de setor ' . $setor->setor_nome . ', com ID #' . formatoId($setor->setor_id, 6) . ' com sucesso');
        }
    }

    public function updating($setor)
    {
        if (auth()->check()) {
            $setor->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Setor $setor): void
    {
        if ($setor && auth()->check()) {
            $changes = $setor->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'setores',
                        'event'      => 'updated',
                        'table_id'   => $setor->setor_id,
                        'table_type' => Setor::class,
                        'column'     => $key,
                        'old_value'  => $setor->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de setor ' . $setor->setor_nome . ', com ID #' . formatoId($setor->setor_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Setor $setor): void
    {
        if ($setor && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'setores',
                'event'      => 'deleted',
                'table_id'   => $setor->setor_id,
                'table_type' => Setor::class,
                'data'       => $setor->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de setor ' . $setor->setor_nome . ', com ID #' . formatoId($setor->setor_id, 6) . ' com sucesso');
        }
    }
}
