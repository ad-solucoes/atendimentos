<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Permissoes;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Detalhes extends Component
{
    public $permissao;

    public function mount($id = null)
    {
        $this->permissao = Permission::find($id);
    }

    public function render()
    {
        return view('livewire.admin.permissoes.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Permissão']);
    }
}
