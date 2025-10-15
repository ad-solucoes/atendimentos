<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Atendimento;
use App\Models\Log;

class AtendimentoObserver
{
    public $afterCommit = true;

    public function creating($atendimento)
    {
        if (auth()->check()) {
            $atendimento->created_user_id = auth()->user()->id;
        }
    }

    public function created(Atendimento $atendimento): void
    {
        if ($atendimento && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'atendimentos',
                'event'      => 'created',
                'table_id'   => $atendimento->atendimento_id,
                'table_type' => Atendimento::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de atendimento ' . $atendimento->atendimento_nome . ', com ID #' . formatoId($atendimento->atendimento_id, 6) . ' com sucesso');
        }
    }

    public function updating($atendimento)
    {
        if (auth()->check()) {
            $atendimento->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Atendimento $atendimento): void
    {
        if ($atendimento && auth()->check()) {
            $changes = $atendimento->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'atendimentos',
                        'event'      => 'updated',
                        'table_id'   => $atendimento->atendimento_id,
                        'table_type' => Atendimento::class,
                        'column'     => $key,
                        'old_value'  => $atendimento->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de atendimento ' . $atendimento->atendimento_nome . ', com ID #' . formatoId($atendimento->atendimento_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Atendimento $atendimento): void
    {
        if ($atendimento && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'atendimentos',
                'event'      => 'deleted',
                'table_id'   => $atendimento->atendimento_id,
                'table_type' => Atendimento::class,
                'data'       => $atendimento->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de atendimento ' . $atendimento->atendimento_nome . ', com ID #' . formatoId($atendimento->atendimento_id, 6) . ' com sucesso');
        }
    }
}
