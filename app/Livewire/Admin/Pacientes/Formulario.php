<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Pacientes;

use App\Models\AgenteSaude;
use App\Models\EquipeSaude;
use App\Models\Paciente;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $paciente_id;

    public $paciente_nome;

    public $equipe_saude_id;

    public $paciente_agente_saude_id;

    public $paciente_sexo;

    public $paciente_data_nascimento;

    public $paciente_nome_mae;

    public $paciente_endereco;

    public $paciente_contato;

    public $paciente_cns;

    public $paciente_cpf;

    public $paciente_status = 1;

    public $equipes_saude = [];

    public $agentes_saude = [];

    protected function rules()
    {
        return [
            'paciente_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('pacientes', 'paciente_nome')->ignore($this->paciente_id, 'paciente_id'),
            ],
            'paciente_cpf'    => 'required|cpf',
            'paciente_cns'    => 'nullable|cns',
            'paciente_status' => 'required',
        ];
    }

    protected $messages = [
        'paciente_nome.required'   => 'O campo "Nome do Paciente" é obrigatório.',
        'paciente_nome.unique'     => 'Este nome do paciente já está em uso.',
        'paciente_status.required' => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        $this->equipes_saude = EquipeSaude::orderBy('equipe_saude_nome')->get();

        if ($id) {
            $doc = Paciente::find($id);

            if ($doc) {
                $this->paciente_id              = $doc->paciente_id;
                $this->paciente_nome            = $doc->paciente_nome;
                $this->equipe_saude_id          = $doc->agente_saude->agente_saude_equipe_saude_id;
                $this->paciente_agente_saude_id = $doc->paciente_agente_saude_id;
                $this->paciente_sexo            = $doc->paciente_sexo;
                $this->paciente_data_nascimento = $doc->paciente_data_nascimento->format('d/m/Y');
                $this->paciente_nome_mae        = $doc->paciente_nome_mae;
                $this->paciente_endereco        = $doc->paciente_endereco;
                $this->paciente_contato         = $doc->paciente_contato;
                $this->paciente_cns             = $doc->paciente_cns;
                $this->paciente_cpf             = $doc->paciente_cpf;
                $this->paciente_status          = $doc->paciente_status;
            }

            if ($this->equipe_saude_id) {
                $this->agentes_saude = AgenteSaude::where('agente_saude_equipe_saude_id', $this->equipe_saude_id)->orderBy('agente_saude_nome')->get();
            }
        }
    }

    public function updatedEquipeSaudeId()
    {
        $this->agentes_saude            = AgenteSaude::where('agente_saude_equipe_saude_id', $this->equipe_saude_id)->orderBy('agente_saude_nome')->get();
        $this->paciente_agente_saude_id = '';
    }

    public function save()
    {
        $this->validate();

        $paciente = Paciente::updateOrCreate(
            ['paciente_id' => $this->paciente_id],
            [
                'paciente_nome'            => $this->paciente_nome,
                'paciente_agente_saude_id' => $this->paciente_agente_saude_id,
                'paciente_sexo'            => $this->paciente_sexo,
                'paciente_data_nascimento' => converteData($this->paciente_data_nascimento),
                'paciente_nome_mae'        => $this->paciente_nome_mae,
                'paciente_endereco'        => $this->paciente_endereco,
                'paciente_contato'         => $this->paciente_contato,
                'paciente_cns'             => $this->paciente_cns,
                'paciente_cpf'             => $this->paciente_cpf,
                'paciente_status'          => $this->paciente_status,
            ]
        );

        session()->flash('message', 'Paciente salvo com sucesso!');

        return redirect()->route('admin.pacientes.detalhes', $paciente->paciente_id);
    }

    public function render()
    {
        return view('livewire.admin.pacientes.formulario')
            ->layout('layouts.admin', ['title' => $this->paciente_id == null ? 'Cadastrar Paciente' : 'Editar Paciente']);
    }
}
