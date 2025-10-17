<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Perfis;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class Detalhes extends Component
{
    public $perfil;

    public function mount($id = null)
    {
        $this->perfil = Role::find($id);
    }

    public function render()
    {
        return view('livewire.admin.perfis.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Perfil']);
    }
}
