<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Estados;

use App\Models\Estado;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $estado_id;

    public $estado_nome;
    public $estado_uf;

    public $estado_status = 1;

    protected function rules()
    {
        return [
            'estado_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('estados', 'estado_nome')->ignore($this->estado_id, 'estado_id'),
            ],
            'estado_uf'  => 'required',
            'estado_status'  => 'required',
        ];
    }

    protected $messages = [
        'estado_nome.required'    => 'O campo "Nome do Estado" é obrigatório.',
        'estado_nome.unique'      => 'Este nome do estado já está em uso.',
        'estado_uf.required'    => 'O campo "UF" é obrigatório.',
        'estado_status.required'  => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = Estado::find($id);

            if ($doc) {
                $this->estado_id      = $doc->estado_id;
                $this->estado_nome    = $doc->estado_nome;
                $this->estado_uf    = $doc->estado_uf;
            }
        }
    }

    public function save()
    {
        $this->validate();

        if($this->estado_id){
            $estado = Estado::find($this->estado_id);
        }else{
            $estado = new Estado();
        }

        $estado->estado_nome            = $this->estado_nome;
        $estado->estado_uf            = $this->estado_uf;
        $estado->estado_status          = $this->estado_status;

        if($estado->save()){
            flash()->success('Estado salvo com sucesso.', [], 'Sucesso!');
        }else{
            flash()->error('Erro ao salvar estado.', [], 'Ossss!');
        }

        return redirect()->route('admin.estados.listagem');
    }

    public function render()
    {
        return view('livewire.admin.estados.formulario')
            ->layout('layouts.admin', ['title' => $this->estado_id == null ? 'Cadastrar Estado' : 'Editar Estado']);
    }
}
