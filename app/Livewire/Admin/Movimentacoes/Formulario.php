<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Movimentacoes;

use App\Models\Procedimento;
use App\Models\Setor;
use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use App\Models\TipoProcedimento;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Formulario extends Component
{
    use WithPagination;

    public $selecionadas = [];

    public $selecionadas_all = false;

    // Campos de movimentação
    public $status = '';

    public $entregue_para = '';

    public $observacao = '';

    public $setor_destino_id = null;

    // Filtros
    public $filtro_status;

    public $filtro_data_inicial;

    public $filtro_data_final;

    public $filtro_periodo = '';

    public $filtro_numero_solicitacao;

    public $filtro_tipo_procedimento;

    public $filtro_procedimento;

    public $filtro_prioridade;

    public $filtro_numero_atendimento;

    public $filtro_nome;

    public $filtro_cpf;

    public $filtro_sus;

    // Controle dinâmico
    public $statusDisponiveis = [];

    public $mostrarEntrega = false;

    protected $paginationTheme = 'personalizado';

    // protected $queryString = [
    //     'filtro_status'            => ['except' => ''],
    //     'filtro_periodo'           => ['except' => ''],
    //     'filtro_numero_solicitacao'      => ['except' => ''],
    //     'filtro_procedimento'      => ['except' => ''],
    //     'filtro_tipo_procedimento' => ['except' => ''],
    //     'page'                     => ['except' => 1],
    // ];

    protected $listeners = ['refreshMovimentacoes' => '$refresh'];

    public function mount()
    {
        if (auth()->user()->hasRole('Recepcao')) {
            $this->filtro_status = 'pendente';
        }

        if (auth()->user()->hasRole('Marcacao')) {
            $this->filtro_status = 'aguardando';
        }
    }

    /** ✅ Atualiza lista de status quando destino muda */
    public function updatedSetorDestinoId($value)
    {
        $this->statusDisponiveis = [];
        $this->mostrarEntrega    = false;

        $setorUsuario = auth()->user()->setor_id ?? null;
        $statusFiltro = $this->filtro_status;

        // Lógica principal
        if ($setorUsuario === 1) { // Recepção
            if ($statusFiltro === 'pendente' && $value == 2) {
                $this->statusDisponiveis = ['aguardando'];
            }

            if ($statusFiltro === 'marcado' && $value == 4) {
                $this->statusDisponiveis = ['entregue'];
                $this->mostrarEntrega    = true;
            }

            if ($statusFiltro === 'devolvido' && $value == 4) {
                $this->statusDisponiveis = ['devolvido'];
                $this->mostrarEntrega    = true;
            }

            if ($statusFiltro === 'cancelado' && $value == 4) {
                $this->statusDisponiveis = ['cancelado'];
                $this->mostrarEntrega    = true;
            }
        }

        if ($setorUsuario === 2) { // Marcação
            if ($statusFiltro === 'aguardando' && $value == 3) {
                $this->statusDisponiveis = ['aguardando'];
            }

            if (in_array($statusFiltro, ['aguardando', 'agendado']) && $value == 1) {
                $this->statusDisponiveis = ['marcado', 'devolvido', 'cancelado'];
            }

            if ($statusFiltro === 'aguardando' && $value == 2) {
                $this->statusDisponiveis = ['agendado'];
            }

            if ($statusFiltro === 'agendado' && $value == 2) {
                $this->statusDisponiveis = ['aguardando'];
            }
        }

        if ($setorUsuario === 3) { // Marcação Externa
            if ($statusFiltro === 'aguardando' && $value == 2) {
                $this->statusDisponiveis = ['aguardando'];
            }
        }
    }

    /** ✅ Atualizar movimentações em massa */
    public function atualizarEmMassa()
    {
        $this->validate([
            'selecionadas' => 'required|array|min:1',
            'status'       => 'required|string',
        ], [
            'selecionadas.required' => 'Selecione ao menos uma solicitação.',
            'status.required'       => 'Escolha o novo status.',
        ]);

        $usuario = auth()->user();

        DB::transaction(function () use ($usuario) {
            foreach ($this->selecionadas as $id) {
                $solicitacao = Solicitacao::find($id);

                if (! $solicitacao) {
                    continue;
                }

                $solicitacao->update([
                    'solicitacao_status'               => $this->status,
                    'solicitacao_localizacao_atual_id' => $this->setor_destino_id,
                    'updated_user_id'                  => $usuario->id,
                ]);

                SolicitacaoMovimentacao::create([
                    'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                    'movimentacao_usuario_id'     => $usuario->id,
                    'movimentacao_destino_id'     => $this->setor_destino_id,
                    'movimentacao_tipo'           => 'encaminhamento',
                    'movimentacao_entregue_para'  => $this->entregue_para ?: null,
                    'movimentacao_observacao'     => $this->observacao,
                    'movimentacao_data'           => now(),
                ]);
            }
        });

        flash()->success('Movimentações registradas com sucesso.', [], 'Sucesso!');

        $this->reset(['selecionadas', 'selecionadas_all', 'status', 'entregue_para', 'observacao', 'setor_destino_id']);
        $this->resetPage();
        $this->dispatch('refreshMovimentacoes');
    }

    /** 🔎 Query principal */
    private function solicitacoesQuery()
    {
        $setorUsuario = auth()->user()->setor_id ?? null;

        return Solicitacao::query()
            ->with(['atendimento.paciente', 'procedimento.tipo_procedimento', 'localizacao_atual'])
            ->when($setorUsuario, fn ($q) => $q->where('solicitacao_localizacao_atual_id', $setorUsuario))
            ->when($this->filtro_status, fn ($q) => $q->where('solicitacao_status', $this->filtro_status));
    }

    public function render()
    {
        $solicitacoes = $this->solicitacoesQuery()->orderByDesc('solicitacao_data')->paginate(25);

        // Determina destinos possíveis
        $usuarioSetor = auth()->user()->setor_id ?? null;
        $destinos     = collect();

        if ($usuarioSetor === 1) { // Recepção
            if ($this->filtro_status === 'pendente') {
                $destinos = Setor::whereIn('setor_id', [2])->get();
            }

            if (in_array($this->filtro_status, ['marcado', 'devolvido', 'cancelado'])) {
                $destinos = Setor::whereIn('setor_id', [4])->get();
            }
        }

        if ($usuarioSetor === 2) { // Marcação
            if ($this->filtro_status === 'aguardando') {
                $destinos = Setor::whereIn('setor_id', [2, 3, 1])->get();
            }

            if ($this->filtro_status === 'agendado') {
                $destinos = Setor::whereIn('setor_id', [2, 1])->get();
            }
        }

        if ($usuarioSetor === 3 && $this->filtro_status === 'aguardando') {
            $destinos = Setor::whereIn('setor_id', [2])->get();
        }

        return view('livewire.admin.movimentacoes.formulario', [
            'solicitacoes'      => $solicitacoes,
            'tipos'             => TipoProcedimento::orderBy('tipo_procedimento_nome')->get(),
            'procedimentos'     => Procedimento::orderBy('procedimento_nome')->get(),
            'destinos'          => $destinos,
            'statusDisponiveis' => collect($this->statusDisponiveis),
            'mostrarEntrega'    => $this->mostrarEntrega,
        ])->layout('layouts.admin', [
            'title' => 'Movimentações de Solicitações',
        ]);
    }
}
