<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Perfis extends Component
{
    public $usuario;

    public array $perfis_selecionados = [];

    public array $perfis_vinculados = [];

    public function mount($id = null)
    {
        $this->usuario             = User::find($id);
        $this->perfis_selecionados = $this->usuario->roles()->pluck('id')->map(function ($id) {
            return (string) $id;
        })->toArray();
    }

    public function syncPerfis()
    {
        $perfis_ids = $this->perfis_selecionados;
        $perfis     = Role::whereIn('id', $perfis_ids)->pluck('name')->toArray();

        if (! empty($perfis)) {
            $this->usuario->syncRoles($perfis);
        } else {
            $this->usuario->syncRoles([]);
        }

        return redirect()->route('admin.usuarios.perfis', $this->usuario->id);
    }

    public function render()
    {
        $perfis = Role::orderBy('name')->get();

        return view('livewire.admin.usuarios.perfis', compact('perfis'))
            ->layout('layouts.admin', ['title' => 'Perfis do Usuário']);
    }
}
