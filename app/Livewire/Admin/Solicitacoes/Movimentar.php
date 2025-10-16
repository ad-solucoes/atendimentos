<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Solicitacoes;

use App\Models\Setor;
use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Movimentar extends Component
{
    public $solicitacao_id;
    public $status = '';
    public $entregue_para = '';
    public $observacao = '';
    public $setor_destino_id = null;

    public $solicitacao;
    public $movimentacoes = [];

    public function mount($solicitacao_id)
    {
        $this->solicitacao_id = $solicitacao_id;
        $this->solicitacao = Solicitacao::with(['localizacao_atual', 'movimentacoes.usuario', 'movimentacoes.destino'])
            ->findOrFail($solicitacao_id);

        $this->status = $this->solicitacao->solicitacao_status ?? 'aguardando';
        $this->carregarMovimentacoes();
    }

    /** 🧾 Carrega todas as movimentações (mais recentes primeiro) */
    private function carregarMovimentacoes(): void
    {
        $this->movimentacoes = $this->solicitacao
            ->movimentacoes()
            ->with(['usuario', 'destino'])
            ->orderByDesc('movimentacao_data')
            ->get();
    }

    public function salvar()
    {
        $this->validate([
            'status' => 'required|string',
        ], [
            'status.required' => 'Selecione o novo status da solicitação.',
        ]);

        $usuario = auth()->user();

        DB::transaction(function () use ($usuario) {
            $solicitacao = $this->solicitacao;

            // Determina o tipo de movimentação conforme status
            $movTipo = $this->determineMovimentacaoTipo($this->status);

            // Define destino (mantém o atual se não informado)
            $novoSetor = $this->setor_destino_id ?: $solicitacao->solicitacao_localizacao_atual_id;

            // Atualiza solicitação
            $solicitacao->update([
                'solicitacao_status'               => $this->status,
                'solicitacao_localizacao_atual_id' => $novoSetor,
                'updated_user_id'                  => $usuario->id,
            ]);

            // Registra movimentação
            SolicitacaoMovimentacao::create([
                'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                'movimentacao_usuario_id'     => $usuario->id,
                'movimentacao_destino_id'     => $novoSetor,
                'movimentacao_tipo'           => $movTipo,
                'movimentacao_entregue_para'  => $this->entregue_para,
                'movimentacao_observacao'     => $this->observacao,
                'movimentacao_data'           => now(),
            ]);
        });

        $this->carregarMovimentacoes();

        flash()->success('Movimentação registrada com sucesso.', [], 'Sucesso!');
        return redirect()->route('admin.solicitacoes.detalhes', $this->solicitacao_id);
    }

    /** Determina o tipo da movimentação */
    private function determineMovimentacaoTipo(string $status): string
    {
        return match ($status) {
            'aguardando' => 'inicial',
            'agendado', 'marcado' => 'encaminhamento',
            'entregue'  => 'entrega',
            'cancelado' => 'cancelamento',
            'devolvido' => 'retorno',
            default     => 'encaminhamento',
        };
    }

    public function render()
    {
        return view('livewire.admin.solicitacoes.movimentar', [
            'setores'       => Setor::orderBy('setor_nome')->get(),
            'solicitacao'   => $this->solicitacao,
            'movimentacoes' => $this->movimentacoes,
        ])->layout('layouts.admin', [
            'title' => 'Movimentar Solicitação',
        ]);
    }
}
