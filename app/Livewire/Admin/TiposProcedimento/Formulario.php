<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\TiposProcedimento;

use App\Models\TipoProcedimento;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $tipo_procedimento_id;

    public $tipo_procedimento_nome;

    public $tipo_procedimento_status = 1;

    protected function rules()
    {
        return [
            'tipo_procedimento_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('tipos_procedimento', 'tipo_procedimento_nome')->ignore($this->tipo_procedimento_id, 'tipo_procedimento_id'),
            ],
            'tipo_procedimento_status' => 'required',
        ];
    }

    protected $messages = [
        'tipo_procedimento_nome.required'   => 'O campo "Nome do Tipo de Procedimento" é obrigatório.',
        'tipo_procedimento_nome.unique'     => 'Este nome do tipo_procedimento já está em uso.',
        'tipo_procedimento_status.required' => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = TipoProcedimento::find($id);

            if ($doc) {
                $this->tipo_procedimento_id   = $doc->tipo_procedimento_id;
                $this->tipo_procedimento_nome = $doc->tipo_procedimento_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        TipoProcedimento::updateOrCreate(
            ['tipo_procedimento_id' => $this->tipo_procedimento_id],
            [
                'tipo_procedimento_nome' => $this->tipo_procedimento_nome,
            ]
        );

        session()->flash('message', 'TipoProcedimento salvo com sucesso!');

        return redirect()->route('admin.tipos_procedimento.listagem');
    }

    public function render()
    {
        return view('livewire.admin.tipos_procedimento.formulario')
            ->layout('layouts.admin', ['title' => $this->tipo_procedimento_id == null ? 'Cadastrar TipoProcedimento' : 'Editar TipoProcedimento']);
    }
}
