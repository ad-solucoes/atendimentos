<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Solicitacoes;

use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use App\Models\Arquivo;
use App\Models\Tipo;
use App\Models\Organizacao;
use App\Models\Etiqueta;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Movimentar extends Component
{
    public $solicitacao_id;
    public $status;
    public $entregue_para;
    public $observacao;

    public function mount($solicitacao_id)
    {
        $this->solicitacao_id = $solicitacao_id;
    }

    public function salvar()
    {
        $solicitacao = Solicitacao::findOrFail($this->solicitacao_id);
        $usuario = auth()->user();

        // registra movimentação
        SolicitacaoMovimentacao::create([
            'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
            'movimentacao_usuario_id' => $usuario->id,
            'movimentacao_tipo' => 'entrega',
            'movimentacao_entregue_para' => $this->entregue_para,
            'movimentacao_observacao' => $this->observacao,
        ]);

        // atualiza solicitação
        $solicitacao->update([
            'solicitacao_status' => $this->status,
        ]);

        session()->flash('message', 'Movimentação registrada com sucesso.');
        return redirect()->route('admin.solicitacoes.detalhes', $solicitacao->solicitacao_id);
    }

    public function render()
    {
        return view('livewire.admin.solicitacoes.movimentar')
            ->layout('layouts.admin', ['title' => 'Movimentar Solicitação']);
    }
}
