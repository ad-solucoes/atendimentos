<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Log;
use App\Models\Solicitacao;

class SolicitacaoObserver
{
    public $afterCommit = true;

    public function creating($solicitacao)
    {
        if (auth()->check()) {
            $solicitacao->created_user_id = auth()->user()->id;
        }
    }

    public function created(Solicitacao $solicitacao): void
    {
        if ($solicitacao && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'solicitacoes',
                'event'      => 'created',
                'table_id'   => $solicitacao->solicitacao_id,
                'table_type' => Solicitacao::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de solicitação número ' . $solicitacao->solicitacao_numero . ', com ID #' . formatoId($solicitacao->solicitacao_id, 6) . ' com sucesso');
        }
    }

    public function updating($solicitacao)
    {
        if (auth()->check()) {
            $solicitacao->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Solicitacao $solicitacao): void
    {
        if ($solicitacao && auth()->check()) {
            $changes = $solicitacao->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'solicitacoes',
                        'event'      => 'updated',
                        'table_id'   => $solicitacao->solicitacao_id,
                        'table_type' => Solicitacao::class,
                        'column'     => $key,
                        'old_value'  => $solicitacao->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de solicitação número ' . $solicitacao->solicitacao_numero . ', com ID #' . formatoId($solicitacao->solicitacao_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Solicitacao $solicitacao): void
    {
        if ($solicitacao && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'solicitacoes',
                'event'      => 'deleted',
                'table_id'   => $solicitacao->solicitacao_id,
                'table_type' => Solicitacao::class,
                'data'       => $solicitacao->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de solicitação número ' . $solicitacao->solicitacao_numero . ', com ID #' . formatoId($solicitacao->solicitacao_id, 6) . ' com sucesso');
        }
    }
}
