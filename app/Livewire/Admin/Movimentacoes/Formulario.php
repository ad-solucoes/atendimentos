<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Movimentacoes;

use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use Livewire\Component;

class Formulario extends Component
{
    public $selecionadas = [];

    public $status;

    public $entregue_para;

    public $observacao;

    public $filtro_status = '';

    public function atualizarEmMassa()
    {
        $usuario = auth()->user();

        foreach ($this->selecionadas as $id) {
            $solicitacao = Solicitacao::find($id);

            if (! $solicitacao) {
                continue;
            }

            SolicitacaoMovimentacao::create([
                'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                'movimentacao_usuario_id'     => $usuario->id,
                'movimentacao_tipo'           => 'atualizado',
                'movimentacao_status_destino' => $this->status,
                'movimentacao_entregue_para'  => $this->entregue_para,
                'movimentacao_observacao'     => $this->observacao,
            ]);

            $solicitacao->update(['solicitacao_status' => $this->status]);
        }

        session()->flash('message', 'Movimentações registradas com sucesso!');
        $this->reset(['selecionadas', 'status', 'entregue_para', 'observacao']);
    }

    public function render()
    {
        $solicitacoes = Solicitacao::when(
            $this->filtro_status,
            fn ($q) => $q->where('solicitacao_status', $this->filtro_status)
        )->latest()->limit(50)->get();

        return view('livewire.admin.movimentacoes.formulario', compact('solicitacoes'))
            ->layout('layouts.admin', ['title' => 'Movimentações em Massa']);
    }
}
