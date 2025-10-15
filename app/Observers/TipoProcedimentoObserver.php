<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Log;
use App\Models\TipoProcedimento;

class TipoProcedimentoObserver
{
    public $afterCommit = true;

    public function creating($tipo_procedimento)
    {
        if (auth()->check()) {
            $tipo_procedimento->created_user_id = auth()->user()->id;
        }
    }

    public function created(TipoProcedimento $tipo_procedimento): void
    {
        if ($tipo_procedimento && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'tipos_procedimento',
                'event'      => 'created',
                'table_id'   => $tipo_procedimento->tipo_procedimento_id,
                'table_type' => TipoProcedimento::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de tipo de procedimento ' . $tipo_procedimento->tipo_procedimento_nome . ', com ID #' . formatoId($tipo_procedimento->tipo_procedimento_id, 6) . ' com sucesso');
        }
    }

    public function updating($tipo_procedimento)
    {
        if (auth()->check()) {
            $tipo_procedimento->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(TipoProcedimento $tipo_procedimento): void
    {
        if ($tipo_procedimento && auth()->check()) {
            $changes = $tipo_procedimento->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'tipos_procedimento',
                        'event'      => 'updated',
                        'table_id'   => $tipo_procedimento->tipo_procedimento_id,
                        'table_type' => TipoProcedimento::class,
                        'column'     => $key,
                        'old_value'  => $tipo_procedimento->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de tipo de procedimento ' . $tipo_procedimento->tipo_procedimento_nome . ', com ID #' . formatoId($tipo_procedimento->tipo_procedimento_id, 6) . ' com sucesso');
        }
    }

    public function deleted(TipoProcedimento $tipo_procedimento): void
    {
        if ($tipo_procedimento && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'tipos_procedimento',
                'event'      => 'deleted',
                'table_id'   => $tipo_procedimento->tipo_procedimento_id,
                'table_type' => TipoProcedimento::class,
                'data'       => $tipo_procedimento->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de tipo de procedimento ' . $tipo_procedimento->tipo_procedimento_nome . ', com ID #' . formatoId($tipo_procedimento->tipo_procedimento_id, 6) . ' com sucesso');
        }
    }
}
