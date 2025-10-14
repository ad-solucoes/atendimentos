<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Pacientes;

use App\Models\Paciente;
use Livewire\Component;

class Detalhes extends Component
{
    public $paciente;

    public function mount($id = null)
    {
        // Carrega o paciente com atendimentos e relações necessárias
        $this->paciente = Paciente::with('atendimentos')->find($id);

        if (! $this->paciente) {
            abort(404, 'Paciente não encontrado');
        }
    }

    public function render()
    {
        return view('livewire.admin.pacientes.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Paciente']);
    }
}
