<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\TiposProcedimento;

use App\Models\TipoProcedimento;
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

    public $tipo_procedimento_id;

    public $tipo_procedimento_nome;

    protected $rules = [
        'tipo_procedimento_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = TipoProcedimento::find($id);

            if ($doc) {
                $this->tipo_procedimento_id      = $doc->tipo_procedimento_id;
                $this->tipo_procedimento_nome            = $doc->tipo_procedimento_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        TipoProcedimento::updateOrCreate(
            ['tipo_procedimento_id' => $this->tipo_procedimento_id],
            [
                'tipo_procedimento_nome'            => $this->tipo_procedimento_nome,
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
