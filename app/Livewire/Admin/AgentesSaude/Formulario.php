<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\AgentesSaude;

use App\Models\AgenteSaude;
use App\Models\EquipeSaude;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $agente_saude_id;

    public $agente_saude_nome;

    public $agente_saude_equipe_saude_id;

    public $equipes_saude;

    public $agente_saude_status = 1;

    protected function rules()
    {
        return [
            'agente_saude_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('agentes_saude', 'agente_saude_nome')->ignore($this->agente_saude_id, 'agente_saude_id'),
            ],
            'agente_saude_equipe_saude_id' => 'required',
            'agente_saude_status' => 'required',
        ];
    }

    protected $messages = [
        'agente_saude_nome.required'   => 'O campo "Nome do Agente de Saúde" é obrigatório.',
        'agente_saude_nome.unique'     => 'Este nome do agente de saúde já está em uso.',
        'agente_saude_equipe_saude_id.required' => 'O campo "Equipe de Saúde " é obrigatório.',
        'agente_saude_status.required' => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        $this->equipes_saude = EquipeSaude::orderBy('equipe_saude_nome')->get();

        if ($id) {
            $doc = AgenteSaude::find($id);

            if ($doc) {
                $this->agente_saude_id              = $doc->agente_saude_id;
                $this->agente_saude_nome            = $doc->agente_saude_nome;
                $this->agente_saude_equipe_saude_id = $doc->agente_saude_equipe_saude_id;
                $this->agente_saude_status = $doc->agente_saude_status;
            }
        }
    }

    public function save()
    {
        $this->validate();

        AgenteSaude::updateOrCreate(
            ['agente_saude_id' => $this->agente_saude_id],
            [
                'agente_saude_nome'            => $this->agente_saude_nome,
                'agente_saude_equipe_saude_id' => $this->agente_saude_equipe_saude_id,
                'agente_saude_status' => $this->agente_saude_status,
            ]
        );

        session()->flash('message', 'AgenteSaude salvo com sucesso!');

        return redirect()->route('admin.agentes_saude.listagem');
    }

    public function render()
    {
        return view('livewire.admin.agentes_saude.formulario')
            ->layout('layouts.admin', ['title' => $this->agente_saude_id == null ? 'Cadastrar Agente de Saúde' : 'Editar Agente de Saúde']);
    }
}
