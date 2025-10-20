<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Estados;

use App\Models\Estado;
use Livewire\Component;

class Detalhes extends Component
{
    public $estado;

    public function mount($id = null)
    {
        $this->estado = Estado::find($id);
    }

    public function render()
    {
        return view('livewire.admin.estados.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Estado']);
    }
}
