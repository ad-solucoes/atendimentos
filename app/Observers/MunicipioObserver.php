<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Log;
use App\Models\Municipio;

class MunicipioObserver
{
    public $afterCommit = true;

    public function creating($municipio)
    {
        if (auth()->check()) {
            $municipio->created_user_id = auth()->user()->id;
        }
    }

    public function created(Municipio $municipio): void
    {
        if ($municipio && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'municipios',
                'event'      => 'created',
                'table_id'   => $municipio->municipio_id,
                'table_type' => Municipio::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de municipio ' . $municipio->municipio_nome . ', com ID #' . formatoId($municipio->municipio_id, 6) . ' com sucesso');
        }
    }

    public function updating($municipio)
    {
        if (auth()->check()) {
            $municipio->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Municipio $municipio): void
    {
        if ($municipio && auth()->check()) {
            $changes = $municipio->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'municipios',
                        'event'      => 'updated',
                        'table_id'   => $municipio->municipio_id,
                        'table_type' => Municipio::class,
                        'column'     => $key,
                        'old_value'  => $municipio->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de municipio ' . $municipio->municipio_nome . ', com ID #' . formatoId($municipio->municipio_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Municipio $municipio): void
    {
        if ($municipio && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'municipios',
                'event'      => 'deleted',
                'table_id'   => $municipio->municipio_id,
                'table_type' => Municipio::class,
                'data'       => $municipio->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de municipio ' . $municipio->municipio_nome . ', com ID #' . formatoId($municipio->municipio_id, 6) . ' com sucesso');
        }
    }
}
