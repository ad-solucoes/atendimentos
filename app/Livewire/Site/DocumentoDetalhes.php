<?php

declare(strict_types = 1);

namespace App\Livewire\Site;

use App\Models\Documento;
use Livewire\Component;

class DocumentoDetalhes extends Component
{
    public $documento;

    public function mount(Documento $documento)
    {
        $this->documento = $documento;
    }

    public function render()
    {
        return view('livewire.site.documento-detalhes')
            ->layout('layouts.site', ['title' => 'Detalhes do documento']);
    }
}
