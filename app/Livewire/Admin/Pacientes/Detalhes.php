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
        $this->paciente = Paciente::find($id);
    }

    public function render()
    {
        return view('livewire.admin.pacientes.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Paciente']);
    }
}
