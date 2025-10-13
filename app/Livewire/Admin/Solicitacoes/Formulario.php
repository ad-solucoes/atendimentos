<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Solicitacoes;

use App\Models\Solicitacao;
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

    public $solicitacao_id;

    public $solicitacao_nome;

    protected $rules = [
        'solicitacao_nome'            => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = Solicitacao::find($id);

            if ($doc) {
                $this->solicitacao_id      = $doc->solicitacao_id;
                $this->solicitacao_nome            = $doc->solicitacao_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        Solicitacao::updateOrCreate(
            ['solicitacao_id' => $this->solicitacao_id],
            [
                'solicitacao_nome'            => $this->solicitacao_nome,
            ]
        );

        session()->flash('message', 'Solicitacao salvo com sucesso!');

        return redirect()->route('admin.solicitacoes.listagem');
    }

    public function render()
    {
        return view('livewire.admin.solicitacoes.formulario')
            ->layout('layouts.admin', ['title' => $this->solicitacao_id == null ? 'Cadastrar Solicitação' : 'Editar Solicitação']);
    }
}
