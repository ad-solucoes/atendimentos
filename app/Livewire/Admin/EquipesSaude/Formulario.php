<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\EquipesSaude;

use App\Models\EquipeSaude;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $equipe_saude_id;

    public $equipe_saude_nome;

    public $equipe_saude_status = 1;

    protected function rules()
    {
        return [
            'equipe_saude_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('equipes_saude', 'equipe_saude_nome')->ignore($this->equipe_saude_id, 'equipe_saude_id'),
            ],
            'equipe_saude_status' => 'required',
        ];
    }

    protected $messages = [
        'equipe_saude_nome.required'   => 'O campo "Nome da Equipe de Saúde" é obrigatório.',
        'equipe_saude_nome.unique'     => 'Este nome do equipe_saude já está em uso.',
        'equipe_saude_status.required' => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = EquipeSaude::find($id);

            if ($doc) {
                $this->equipe_saude_id     = $doc->equipe_saude_id;
                $this->equipe_saude_nome   = $doc->equipe_saude_nome;
                $this->equipe_saude_status = $doc->equipe_saude_status;
            }
        }
    }

    public function save()
    {
        $this->validate();

        EquipeSaude::updateOrCreate(
            ['equipe_saude_id' => $this->equipe_saude_id],
            [
                'equipe_saude_nome'   => $this->equipe_saude_nome,
                'equipe_saude_status' => $this->equipe_saude_status,
            ]
        );

        flash()->success('Equipe de saúde salvo com sucesso.', [], 'Sucesso!');

        return redirect()->route('admin.equipes_saude.listagem');
    }

    public function render()
    {
        return view('livewire.admin.equipes_saude.formulario')
            ->layout('layouts.admin', ['title' => $this->equipe_saude_id == null ? 'Cadastrar Equipe de Saúude' : 'Editar Equipe de Saúde']);
    }
}
