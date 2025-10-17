<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Perfis;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Permissoes extends Component
{
    public $perfil;

    public array $permissoes_selecionados = [];

    public array $permissoes_vinculadas = [];

    public function mount($id = null)
    {
        $this->perfil                  = Role::find($id);
        $this->permissoes_selecionados = $this->perfil->permissions()->pluck('id')->map(function ($id) {
            return (string) $id;
        })->toArray();
    }

    public function syncPermissoes()
    {
        $permissoes_ids = $this->permissoes_selecionados;
        $permissoes     = Permission::whereIn('id', $permissoes_ids)->pluck('name')->toArray();

        if (! empty($permissoes)) {
            $this->perfil->syncPermissions($permissoes);
        } else {
            $this->perfil->syncPermissions([]);
        }

        return redirect()->route('admin.perfis.permissoes', $this->perfil->id);
    }

    public function render()
    {
        $permissoes = Permission::all();

        return view('livewire.admin.perfis.permissoes', compact('permissoes'))
            ->layout('layouts.admin', ['title' => 'Permissões do Perfil']);
    }
}
