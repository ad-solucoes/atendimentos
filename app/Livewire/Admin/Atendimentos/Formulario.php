<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
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

    public $atendimento_id;

    public $atendimento_nome;

    protected $rules = [
        'atendimento_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = Atendimento::find($id);

            if ($doc) {
                $this->atendimento_id      = $doc->atendimento_id;
                $this->atendimento_nome            = $doc->atendimento_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        Atendimento::updateOrCreate(
            ['atendimento_id' => $this->atendimento_id],
            [
                'atendimento_nome'            => $this->atendimento_nome,
            ]
        );

        session()->flash('message', 'Atendimento salvo com sucesso!');

        return redirect()->route('admin.atendimentos.listagem');
    }

    public function render()
    {
        return view('livewire.admin.atendimentos.formulario')
            ->layout('layouts.admin', ['title' => $this->atendimento_id == null ? 'Cadastrar Atendimento' : 'Editar Atendimento']);
    }
}
