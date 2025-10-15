<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\AgenteSaude;
use App\Models\Log;

class AgenteSaudeObserver
{
    public $afterCommit = true;

    public function creating($agente_saude)
    {
        if (auth()->check()) {
            $agente_saude->created_user_id = auth()->user()->id;
        }
    }

    public function created(AgenteSaude $agente_saude): void
    {
        if ($agente_saude && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'agentes_saude',
                'event'      => 'created',
                'table_id'   => $agente_saude->agente_saude_id,
                'table_type' => AgenteSaude::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de agente de saúde ' . $agente_saude->agente_saude_nome . ', com ID #' . formatoId($agente_saude->agente_saude_id, 6) . ' com sucesso');
        }
    }

    public function updating($agente_saude)
    {
        if (auth()->check()) {
            $agente_saude->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(AgenteSaude $agente_saude): void
    {
        if ($agente_saude && auth()->check()) {
            $changes = $agente_saude->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'agentes_saude',
                        'event'      => 'updated',
                        'table_id'   => $agente_saude->agente_saude_id,
                        'table_type' => AgenteSaude::class,
                        'column'     => $key,
                        'old_value'  => $agente_saude->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de agente de saúde ' . $agente_saude->agente_saude_nome . ', com ID #' . formatoId($agente_saude->agente_saude_id, 6) . ' com sucesso');
        }
    }

    public function deleted(AgenteSaude $agente_saude): void
    {
        if ($agente_saude && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'agentes_saude',
                'event'      => 'deleted',
                'table_id'   => $agente_saude->agente_saude_id,
                'table_type' => AgenteSaude::class,
                'data'       => $agente_saude->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de agente de saúde ' . $agente_saude->agente_saude_nome . ', com ID #' . formatoId($agente_saude->agente_saude_id, 6) . ' com sucesso');
        }
    }
}
