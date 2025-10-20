<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Permissoes;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class Formulario extends Component
{
    public $permissao_id;

    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required',
                Rule::unique('permissions', 'name')->ignore($this->permissao_id, 'id'),
            ],
        ];
    }

    protected $messages = [
        'name.required' => 'O campo "Nome da Permissão" é obrigatório.',
        'name.unique'   => 'Este nome da permissão já está em uso.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $permissao = Permission::find($id);

            $this->permissao_id = $permissao->id;
            $this->name         = $permissao->name;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->permissao_id) {
            $permissao = Permission::find($this->permissao_id);
        } else {
            $permissao = new Permission();
        }

        $permissao->name = $this->name;

        if ($permissao->save()) {
            flash()->success('Permissão salvo com sucesso.', [], 'Sucesso!');
        } else {
            flash()->error('Erro ao salvar permissão.', [], 'Opssss!');
        }

        return redirect()->route('admin.permissoes.listagem');
    }

    public function render()
    {
        return view('livewire.admin.permissoes.formulario')->layout('layouts.admin', ['title' => $this->permissao_id == null ? 'Cadastrar Permissão' : 'Editar Permissão']);
    }
}
