<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Procedimentos;

use App\Models\Procedimento;
use App\Models\TipoProcedimento;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $procedimento_id;

    public $procedimento_nome;

    public $procedimento_tipo_id;

    public $procedimento_status = 1;

    public $tipos_procedimento;

    protected function rules()
    {
        return [
            'procedimento_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('procedimentos', 'procedimento_nome')->ignore($this->procedimento_id, 'procedimento_id'),
            ],
            'procedimento_tipo_id' => 'required',
            'procedimento_status'  => 'required',
        ];
    }

    protected $messages = [
        'procedimento_nome.required'    => 'O campo "Nome do Procedimento" é obrigatório.',
        'procedimento_nome.unique'      => 'Este nome do procedimento já está em uso.',
        'procedimento_tipo_id.required' => 'O campo "Tipo de Procedimento" é obrigatório.',
        'procedimento_status.required'  => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        $this->tipos_procedimento = TipoProcedimento::orderBy('tipo_procedimento_nome')->get();

        if ($id) {
            $doc = Procedimento::find($id);

            if ($doc) {
                $this->procedimento_id      = $doc->procedimento_id;
                $this->procedimento_nome    = $doc->procedimento_nome;
                $this->procedimento_tipo_id = $doc->procedimento_tipo_id;
            }
        }
    }

    public function save()
    {
        $this->validate();

        Procedimento::updateOrCreate(
            ['procedimento_id' => $this->procedimento_id],
            [
                'procedimento_nome'    => $this->procedimento_nome,
                'procedimento_tipo_id' => $this->procedimento_tipo_id,
            ]
        );

        session()->flash('message', 'Procedimento salvo com sucesso!');

        return redirect()->route('admin.procedimentos.listagem');
    }

    public function render()
    {
        return view('livewire.admin.procedimentos.formulario')
            ->layout('layouts.admin', ['title' => $this->procedimento_id == null ? 'Cadastrar Procedimento' : 'Editar Procedimento']);
    }
}
