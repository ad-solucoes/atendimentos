<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;

class Detalhes extends Component
{
    public $usuario;

    public function mount($id = null)
    {
        $this->usuario = User::find($id);
    }

    public function render()
    {
        return view('livewire.admin.usuarios.detalhes')
            ->layout('layouts.admin', ['title' => 'Detalhes do Usuário']);
    }
}
