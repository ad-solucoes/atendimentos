<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Pacientes;

use App\Models\AgenteSaude;
use App\Models\Paciente;
use App\Models\Arquivo;
use App\Models\Tipo;
use App\Models\Organizacao;
use App\Models\Etiqueta;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{
    use WithFileUploads;

    public $paciente_id;

    public $paciente_nome;
    public $paciente_agente_saude_id;
    public $paciente_sexo;
    public $paciente_data_nascimento;
    public $paciente_nome_mae;
    public $paciente_endereco;
    public $paciente_contato;
    public $paciente_cns;
    public $paciente_cpf;

    public $agentes_saude;

    protected $rules = [
        'paciente_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        $this->agentes_saude = AgenteSaude::orderBy('agente_saude_nome')->get();

        if ($id) {
            $doc = Paciente::find($id);

            if ($doc) {
                $this->paciente_id      = $doc->paciente_id;
                $this->paciente_nome            = $doc->paciente_nome;
                $this->paciente_agente_saude_id            = $doc->paciente_agente_saude_id;
                $this->paciente_sexo            = $doc->paciente_sexo;
                $this->paciente_data_nascimento            = $doc->paciente_data_nascimento->format('Y-m-d');
                $this->paciente_nome_mae            = $doc->paciente_nome_mae;
                $this->paciente_endereco            = $doc->paciente_endereco;
                $this->paciente_contato            = $doc->paciente_contato;
                $this->paciente_cns            = $doc->paciente_cns;
                $this->paciente_cpf            = $doc->paciente_cpf;
            }
        }
    }

    public function save()
    {
        $this->validate();

        Paciente::updateOrCreate(
            ['paciente_id' => $this->paciente_id],
            [
                'paciente_nome'            => $this->paciente_nome,
                'paciente_agente_saude_id'            => $this->paciente_agente_saude_id,
                'paciente_sexo'            => $this->paciente_sexo,
                'paciente_data_nascimento'            => $this->paciente_data_nascimento,
                'paciente_nome_mae'            => $this->paciente_nome_mae,
                'paciente_endereco'            => $this->paciente_endereco,
                'paciente_contato'            => $this->paciente_contato,
                'paciente_cns'            => $this->paciente_cns,
                'paciente_cpf'            => $this->paciente_cpf,
            ]
        );

        session()->flash('message', 'Paciente salvo com sucesso!');

        return redirect()->route('admin.pacientes.listagem');
    }

    public function render()
    {
        return view('livewire.admin.pacientes.formulario')
            ->layout('layouts.admin', ['title' => $this->paciente_id == null ? 'Cadastrar Paciente' : 'Editar Paciente']);
    }
}
