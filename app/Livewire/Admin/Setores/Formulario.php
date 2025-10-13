<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Setores;

use App\Models\Setor;
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

    public $setor_id;

    public $setor_nome;

    protected $rules = [
        'setor_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = Setor::find($id);

            if ($doc) {
                $this->setor_id      = $doc->setor_id;
                $this->setor_nome            = $doc->setor_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        Setor::updateOrCreate(
            ['setor_id' => $this->setor_id],
            [
                'setor_nome'            => $this->setor_nome,
            ]
        );

        session()->flash('message', 'Setor salvo com sucesso!');

        return redirect()->route('admin.setores.listagem');
    }

    public function render()
    {
        return view('livewire.admin.setores.formulario')
            ->layout('layouts.admin', ['title' => $this->setor_id == null ? 'Cadastrar Setor' : 'Editar Setor']);
    }
}
