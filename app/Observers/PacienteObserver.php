<?php

declare(strict_types = 1);

namespace App\Observers;

use App\Models\Paciente;
use App\Models\Log;

class PacienteObserver
{
    public $afterCommit = true;

    public function creating($paciente)
    {
        if (auth()->check()) {
            $paciente->created_user_id = auth()->user()->id;
        }
    }

    public function created(Paciente $paciente): void
    {
        if ($paciente && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'pacientes',
                'event'      => 'created',
                'table_id'   => $paciente->paciente_id,
                'table_type' => Paciente::class,
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Cadastro de paciente ' . $paciente->paciente_nome . ', com ID #' . formatoId($paciente->paciente_id, 6) . ' com sucesso');
        }
    }

    public function updating($paciente)
    {
        if (auth()->check()) {
            $paciente->updated_user_id = auth()->user()->id;
        }
    }

    public function updated(Paciente $paciente): void
    {
        if ($paciente && auth()->check()) {
            $changes = $paciente->getChanges();

            foreach ($changes as $key => $value) {
                if ($key != 'updated_user_id' and $key != 'updated_at') {
                    auth()->user()->audits()->create([
                        'table'      => 'pacientes',
                        'event'      => 'updated',
                        'table_id'   => $paciente->paciente_id,
                        'table_type' => Paciente::class,
                        'column'     => $key,
                        'old_value'  => $paciente->getOriginal($key),
                        'new_value'  => $value,
                        'ip'         => request()->ip(),
                    ]);
                }
            }

            Log::logMessage('Alteração de paciente ' . $paciente->paciente_nome . ', com ID #' . formatoId($paciente->paciente_id, 6) . ' com sucesso');
        }
    }

    public function deleted(Paciente $paciente): void
    {
        if ($paciente && auth()->check()) {
            auth()->user()->audits()->create([
                'table'      => 'pacientes',
                'event'      => 'deleted',
                'table_id'   => $paciente->paciente_id,
                'table_type' => Paciente::class,
                'data'       => $paciente->toArray(),
                'ip'         => request()->ip(),
            ]);

            Log::logMessage('Exclusão de paciente ' . $paciente->paciente_nome . ', com ID #' . formatoId($paciente->paciente_id, 6) . ' com sucesso');
        }
    }
}
