<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Pacientes;

use App\Models\AgenteSaude;
use App\Models\EquipeSaude;
use App\Models\Estado;
use App\Models\Municipio;
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
    public $paciente_numero;
    public $paciente_bairro;
    public $paciente_complemento;

    public $estado_id;

    public $paciente_municipio_id;

    public $paciente_contato_01;
    public $paciente_contato_02;
    public $paciente_email;

    public $paciente_cartao_sus;

    public $paciente_cpf;

    public $paciente_status = 1;

    public $equipes_saude = [];

    public $agentes_saude = [];

    public $estados = [];

    public $municipios = [];

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
            'paciente_cartao_sus'    => 'nullable|cns',
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
        $this->estados = Estado::orderBy('estado_nome')->get();

        if ($id) {
            $paciente = Paciente::find($id);

            if ($paciente) {
                $this->paciente_id              = $paciente->paciente_id;
                $this->paciente_nome            = $paciente->paciente_nome;
                $this->equipe_saude_id          = $paciente->agente_saude->agente_saude_equipe_saude_id;
                $this->paciente_agente_saude_id = $paciente->paciente_agente_saude_id;
                $this->paciente_sexo            = $paciente->paciente_sexo;
                $this->paciente_data_nascimento = $paciente->paciente_data_nascimento->format('d/m/Y');
                $this->paciente_nome_mae        = $paciente->paciente_nome_mae;
                $this->paciente_endereco        = $paciente->paciente_endereco;
                $this->paciente_numero        = $paciente->paciente_numero;
                $this->paciente_bairro        = $paciente->paciente_bairro;
                $this->estado_id        = $paciente->municipio?->municipio_estado_id;
                $this->paciente_municipio_id        = $paciente->paciente_municipio_id;
                $this->paciente_contato_01         = $paciente->paciente_contato_01;
                $this->paciente_contato_02         = $paciente->paciente_contato_02;
                $this->paciente_email         = $paciente->paciente_email;
                $this->paciente_cartao_sus             = $paciente->paciente_cartao_sus;
                $this->paciente_cpf             = $paciente->paciente_cpf;
                $this->paciente_status          = $paciente->paciente_status;
            }

            if ($this->equipe_saude_id) {
                $this->agentes_saude = AgenteSaude::where('agente_saude_equipe_saude_id', $this->equipe_saude_id)->orderBy('agente_saude_nome')->get();
            }

            if ($this->estado_id) {
                $this->municipios = Municipio::where('municipio_estado_id', $this->estado_id)->orderBy('municipio_nome')->get();
            }
        }
    }

    public function updatedEquipeSaudeId()
    {
        $this->agentes_saude            = AgenteSaude::where('agente_saude_equipe_saude_id', $this->equipe_saude_id)->orderBy('agente_saude_nome')->get();
        $this->paciente_agente_saude_id = '';
    }

    public function updatedEstadoId()
    {
        $this->municipios = Municipio::where('municipio_estado_id', $this->estado_id)->orderBy('municipio_nome')->get();
        $this->paciente_municipio_id = '';
    }

    public function save()
    {
        $this->validate();

        if($this->paciente_id){
            $paciente = Paciente::find($this->paciente_id);
        }else{
            $paciente = new Paciente();
        }

        $paciente->paciente_nome            = $this->paciente_nome;
        $paciente->paciente_agente_saude_id = $this->paciente_agente_saude_id;
        $paciente->paciente_sexo           = $this->paciente_sexo;
        $paciente->paciente_data_nascimento = converteData($this->paciente_data_nascimento);
        $paciente->paciente_nome_mae       = $this->paciente_nome_mae;
        $paciente->paciente_endereco       = $this->paciente_endereco;
        $paciente->paciente_numero       = $this->paciente_numero;
        $paciente->paciente_bairro       = $this->paciente_bairro;
        $paciente->paciente_complemento       = $this->paciente_complemento;
        $paciente->paciente_municipio_id       = $this->paciente_municipio_id;
        $paciente->paciente_cartao_sus            = $this->paciente_cartao_sus;
        $paciente->paciente_cpf            = $this->paciente_cpf;
        $paciente->paciente_contato_01            = $this->paciente_contato_01;
        $paciente->paciente_contato_02            = $this->paciente_contato_02;
        $paciente->paciente_email            = $this->paciente_email;
        $paciente->paciente_status         = $this->paciente_status;

        if($paciente->save()){
            flash()->success('Paciente salvo com sucesso.', [], 'Sucesso!');
        }else{
            flash()->error('Erro ao salvar paciente.', [], 'Ossss!');
        }

        return redirect()->route('admin.pacientes.detalhes', $paciente->paciente_id);
    }

    public function render()
    {
        return view('livewire.admin.pacientes.formulario')
            ->layout('layouts.admin', ['title' => $this->paciente_id == null ? 'Cadastrar Paciente' : 'Editar Paciente']);
    }
}
