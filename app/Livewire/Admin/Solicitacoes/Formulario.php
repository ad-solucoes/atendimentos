<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Solicitacoes;

use App\Models\Solicitacao;
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{
    use WithFileUploads;

    public $solicitacao_id;

    public $solicitacao_nome;

    protected $rules = [
        'solicitacao_nome' => 'required|string|max:150',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $doc = Solicitacao::find($id);

            if ($doc) {
                $this->solicitacao_id   = $doc->solicitacao_id;
                $this->solicitacao_nome = $doc->solicitacao_nome;
            }
        }
    }

    public function save()
    {
        $this->validate();

        $solicitacao = Solicitacao::updateOrCreate(
            ['solicitacao_id' => $this->solicitacao_id],
            [
                'solicitacao_nome' => $this->solicitacao_nome,
            ]
        );

        flash()->success('Solicitação salvo com sucesso.', [], 'Sucesso!');

        return redirect()->route('admin.solicitacoes.detalhes', $solicitacao->solicitacao_id);
    }

    public function render()
    {
        return view('livewire.admin.solicitacoes.formulario')
            ->layout('layouts.admin', ['title' => $this->solicitacao_id == null ? 'Cadastrar Solicitação' : 'Editar Solicitação']);
    }
}
