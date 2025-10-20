<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Perfis;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Formulario extends Component
{
    public $perfil_id;

    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($this->perfil_id, 'id'),
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'O campo "Nome do Perfil" é obrigatório.',
        'name.unique'   => 'Este nome do perfil já está em uso.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $perfil = Role::find($id);

            $this->perfil_id = $perfil->id;
            $this->name      = $perfil->name;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->perfil_id) {
            $perfil = Role::find($this->perfil_id);
        } else {
            $perfil = new Role();
        }

        $perfil->name = $this->name;

        if ($perfil->save()) {
            flash()->success('Perfil salvo com sucesso.', [], 'Sucesso!');
        } else {
            flash()->error('Erro ao salvar perfil.', [], 'Opssss!');
        }

        return redirect()->route('admin.perfis.listagem');
    }

    public function render()
    {
        return view('livewire.admin.perfis.formulario')->layout('layouts.admin', ['title' => $this->perfil_id == null ? 'Cadastrar Perfil' : 'Editar Perfil']);
    }
}
