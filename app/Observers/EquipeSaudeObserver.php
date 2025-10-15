<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\EquipeSaude;
use App\Models\Log;

class EquipeSaudeObserver
{
    public $afterCommit = true;

    public function creating($equipe_saude)
    {
        if (auth()->check()) {
            $equipe_saude->created_user_id = auth()->user()->id;
        }
    }

    public function created(EquipeSaude $equipe_saude): void
    {
        if ($equipe_saude && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'equipes_saude',
                'event'      => 'created',
                'table_id'   => $equipe_saude->equipe_saude_id,
                'table_type' => EquipeSaude::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de equipe de saúde ' . $equipe_saude->equipe_saude_nome . ', com ID #' . formatoId($equipe_saude->equipe_saude_id, 6) . ' com sucesso');
        }
    }

    public function updating($equipe_saude)
    {
        if (auth()->check()) {
            $equipe_saude->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(EquipeSaude $equipe_saude): void
    {
        if ($equipe_saude && auth()->check()) {
            $changes = $equipe_saude->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'equipes_saude',
                        'event'      => 'updated',
                        'table_id'   => $equipe_saude->equipe_saude_id,
                        'table_type' => EquipeSaude::class,
                        'column'     => $key,
                        'old_value'  => $equipe_saude->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de equipe de saúde ' . $equipe_saude->equipe_saude_nome . ', com ID #' . formatoId($equipe_saude->equipe_saude_id, 6) . ' com sucesso');
        }
    }

    public function deleted(EquipeSaude $equipe_saude): void
    {
        if ($equipe_saude && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'equipes_saude',
                'event'      => 'deleted',
                'table_id'   => $equipe_saude->equipe_saude_id,
                'table_type' => EquipeSaude::class,
                'data'       => $equipe_saude->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de equipe de saúde ' . $equipe_saude->equipe_saude_nome . ', com ID #' . formatoId($equipe_saude->equipe_saude_id, 6) . ' com sucesso');
        }
    }
}
