<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\AgentesSaude;

use App\Models\AgenteSaude;
use Livewire\Component;

class Detalhes extends Component
{
    public $agente_saude;

    public function mount($id = null)
    {
        $this->agente_saude = AgenteSaude::find($id);
    }

    public function render()
    {
        return view('livewire.admin.agentes_saude.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Agente de Saúde']);
    }
}
