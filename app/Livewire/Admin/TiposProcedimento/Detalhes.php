<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\TiposProcedimento;

use App\Models\TipoProcedimento;
use Livewire\Component;

class Detalhes extends Component
{
    public $tipo_procedimento;

    public function mount($id = null)
    {
        $this->tipo_procedimento = TipoProcedimento::find($id);
    }

    public function render()
    {
        return view('livewire.admin.tipos_procedimento.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Tipo de Procedimento']);
    }
}
