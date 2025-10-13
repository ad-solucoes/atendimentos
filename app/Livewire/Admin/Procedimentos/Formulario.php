<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Procedimentos;

use App\Models\Procedimento;
use App\Models\Arquivo;
use App\Models\Tipo;
use App\Models\Organizacao;
use App\Models\Etiqueta;
use App\Models\TipoProcedimento;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{
    use WithFileUploads;

    public $procedimento_id;

    public $procedimento_nome;
    public $procedimento_tipo_id;

    public $tipos_procedimento;

    protected $rules = [
        'procedimento_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        $this->tipos_procedimento = TipoProcedimento::orderBy('tipo_procedimento_nome')->get();

        if ($id) {
            $doc = Procedimento::find($id);

            if ($doc) {
                $this->procedimento_id      = $doc->procedimento_id;
                $this->procedimento_nome            = $doc->procedimento_nome;
                $this->procedimento_tipo_id            = $doc->procedimento_tipo_id;
            }
        }
    }

    public function save()
    {
        $this->validate();

        Procedimento::updateOrCreate(
            ['procedimento_id' => $this->procedimento_id],
            [
                'procedimento_nome'            => $this->procedimento_nome,
                'procedimento_tipo_id'            => $this->procedimento_tipo_id,
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
