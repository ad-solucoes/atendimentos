<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
use Livewire\Component;

class Detalhes extends Component
{
    public $atendimento;

    public function mount($id = null)
    {
        $this->atendimento = Atendimento::find($id);
    }

    public function render()
    {
        return view('livewire.admin.atendimentos.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Atendimento']);
    }
}
