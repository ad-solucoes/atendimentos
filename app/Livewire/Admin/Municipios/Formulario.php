<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Municipios;

use App\Models\Municipio;
use App\Models\Estado;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public $municipio_id;

    public $municipio_nome;

    public $municipio_estado_id;

    public $municipio_status = 1;

    public $estados;

    protected function rules()
    {
        return [
            'municipio_nome' => [
                'required',
                'string',
                'max:150',
                Rule::unique('municipios', 'municipio_nome')->ignore($this->municipio_id, 'municipio_id'),
            ],
            'municipio_estado_id' => 'required',
            'municipio_status'  => 'required',
        ];
    }

    protected $messages = [
        'municipio_nome.required'    => 'O campo "Nome do Municipio" é obrigatório.',
        'municipio_nome.unique'      => 'Este nome do municipio já está em uso.',
        'municipio_estado_id.required' => 'O campo "Tipo de Municipio" é obrigatório.',
        'municipio_status.required'  => 'O campo "Status" é obrigatório.',
    ];

    public function mount($id = null)
    {
        $this->estados = Estado::orderBy('estado_nome')->get();

        if ($id) {
            $doc = Municipio::find($id);

            if ($doc) {
                $this->municipio_id      = $doc->municipio_id;
                $this->municipio_nome    = $doc->municipio_nome;
                $this->municipio_estado_id = $doc->municipio_estado_id;
            }
        }
    }

    public function save()
    {
        $this->validate();

        if($this->municipio_id){
            $municipio = Municipio::find($this->municipio_id);
        }else{
            $municipio = new Municipio();
        }

        $municipio->municipio_nome            = $this->municipio_nome;
        $municipio->municipio_estado_id = $this->municipio_estado_id;
        $municipio->municipio_status          = $this->municipio_status;

        if($municipio->save()){
            flash()->success('Municipio salvo com sucesso.', [], 'Sucesso!');
        }else{
            flash()->error('Erro ao salvar municipio.', [], 'Ossss!');
        }

        return redirect()->route('admin.municipios.listagem');
    }

    public function render()
    {
        return view('livewire.admin.municipios.formulario')
            ->layout('layouts.admin', ['title' => $this->municipio_id == null ? 'Cadastrar Municipio' : 'Editar Municipio']);
    }
}
