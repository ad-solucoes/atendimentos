<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Procedimentos;

use App\Models\Procedimento;
use Livewire\Component;

class Detalhes extends Component
{
    public $procedimento;

    public function mount($id = null)
    {
        $this->procedimento = Procedimento::find($id);
    }

    public function render()
    {
        return view('livewire.admin.procedimentos.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Procedimento']);
    }
}
