<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Setores;

use App\Models\Setor;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $setor_id;

    public $setor_nome;

    public $setor_status = 1;

    protected function rules()
    {
        return [
            'setor_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('setores', 'setor_nome')->ignore($this->setor_id, 'setor_id'),
            ],
            'setor_status' => 'required',
        ];
    }

    protected $messages = [
        'setor_nome.required'   => 'O campo "Nome do Setor" é obrigatório.',
        'setor_nome.unique'     => 'Este nome do setor já está em uso.',
        'setor_status.required' => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = Setor::find($id);

            if ($doc) {
                $this->setor_id     = $doc->setor_id;
                $this->setor_nome   = $doc->setor_nome;
                $this->setor_status = $doc->setor_status;
            }
        }
    }

    public function save()
    {
        $this->validate();

        if($this->setor_id){
            $setor = Setor::find($this->setor_id);
        }else{
            $setor = new Setor();
        }

        $setor->setor_nome            = $this->setor_nome;
        $setor->setor_status          = $this->setor_status;

        if($setor->save()){
            flash()->success('Setor salvo com sucesso.', [], 'Sucesso!');
        }else{
            flash()->error('Erro ao salvar setor.', [], 'Ossss!');
        }

        return redirect()->route('admin.setores.listagem');
    }

    public function render()
    {
        return view('livewire.admin.setores.formulario')
            ->layout('layouts.admin', ['title' => $this->setor_id == null ? 'Cadastrar Setor' : 'Editar Setor']);
    }
}
