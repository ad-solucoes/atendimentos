<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Log;
use App\Models\Procedimento;

class ProcedimentoObserver
{
    public $afterCommit = true;

    public function creating($procedimento)
    {
        if (auth()->check()) {
            $procedimento->created_user_id = auth()->user()->id;
        }
    }

    public function created(Procedimento $procedimento): void
    {
        if ($procedimento && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'procedimentos',
                'event'      => 'created',
                'table_id'   => $procedimento->procedimento_id,
                'table_type' => Procedimento::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de procedimento ' . $procedimento->procedimento_nome . ', com ID #' . formatoId($procedimento->procedimento_id, 6) . ' com sucesso');
        }
    }

    public function updating($procedimento)
    {
        if (auth()->check()) {
            $procedimento->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Procedimento $procedimento): void
    {
        if ($procedimento && auth()->check()) {
            $changes = $procedimento->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'procedimentos',
                        'event'      => 'updated',
                        'table_id'   => $procedimento->procedimento_id,
                        'table_type' => Procedimento::class,
                        'column'     => $key,
                        'old_value'  => $procedimento->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de procedimento ' . $procedimento->procedimento_nome . ', com ID #' . formatoId($procedimento->procedimento_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Procedimento $procedimento): void
    {
        if ($procedimento && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'procedimentos',
                'event'      => 'deleted',
                'table_id'   => $procedimento->procedimento_id,
                'table_type' => Procedimento::class,
                'data'       => $procedimento->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de procedimento ' . $procedimento->procedimento_nome . ', com ID #' . formatoId($procedimento->procedimento_id, 6) . ' com sucesso');
        }
    }
}
