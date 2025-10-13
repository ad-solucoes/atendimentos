<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Setores;

use App\Models\Setor;
use Livewire\Component;

class Detalhes extends Component
{
    public $setor;

    public function mount($id = null)
    {
        $this->setor = Setor::find($id);
    }

    public function render()
    {
        return view('livewire.admin.setores.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Setor']);
    }
}
