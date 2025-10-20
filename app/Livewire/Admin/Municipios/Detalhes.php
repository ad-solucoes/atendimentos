<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Municipios;

use App\Models\Municipio;
use Livewire\Component;

class Detalhes extends Component
{
    public $municipio;

    public function mount($id = null)
    {
        $this->municipio = Municipio::find($id);
    }

    public function render()
    {
        return view('livewire.admin.municipios.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Município']);
    }
}
