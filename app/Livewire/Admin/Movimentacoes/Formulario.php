<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Movimentacoes;

use App\Models\Procedimento;
use App\Models\Setor;
use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use App\Models\TipoProcedimento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
    public $filtro_status = '';
    public $filtro_data_inicial;
    public $filtro_data_final;
    public $filtro_periodo = '';
    public $filtro_tipo_procedimento;
    public $filtro_procedimento;
    public $filtro_prioridade;
    public $filtro_numero_atendimento;
    public $filtro_nome;
    public $filtro_cpf;
    public $filtro_sus;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'filtro_status'            => ['except' => ''],
        'filtro_periodo'           => ['except' => ''],
        'filtro_procedimento'      => ['except' => ''],
        'filtro_tipo_procedimento' => ['except' => ''],
        'page'                     => ['except' => 1],
    ];

    protected $listeners = ['refreshMovimentacoes' => '$refresh'];

    /** ✅ Marcar todas as solicitações da página */
    public function updatedSelecionadasAll($value)
    {
        $this->selecionadas = $value
            ? $this->solicitacoesQuery()->pluck('solicitacao_id')->map(fn($v) => (string)$v)->toArray()
            : [];
    }

    /** ✅ Desmarca “selecionar todas” se alguma for retirada */
    public function updatedSelecionadas()
    {
        if (count($this->selecionadas) === 0) {
            $this->selecionadas_all = false;
        }
    }

    /** ✅ Resetar filtros */
    public function resetarFiltros()
    {
        $this->reset([
            'filtro_status',
            'filtro_data_inicial',
            'filtro_data_final',
            'filtro_periodo',
            'filtro_tipo_procedimento',
            'filtro_procedimento',
            'filtro_prioridade',
            'filtro_numero_atendimento',
            'filtro_nome',
            'filtro_cpf',
            'filtro_sus',
        ]);
        $this->resetPage();
    }

    /** ✅ Atualiza várias solicitações de uma vez */
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
        $setorUsuario = $usuario->setor_id ?? null;

        DB::transaction(function () use ($usuario, $setorUsuario) {
            foreach ($this->selecionadas as $id) {
                $solicitacao = Solicitacao::find($id);
                if (! $solicitacao) continue;

                // Define destino conforme regras do PDF
                $novoSetor = $this->determinarSetorDestino($this->status, $setorUsuario);
                $movTipo   = $this->determinarMovimentacaoTipo($this->status, $setorUsuario);

                $solicitacao->update([
                    'solicitacao_status'               => $this->status,
                    'solicitacao_localizacao_atual_id' => $novoSetor,
                    'updated_user_id'                  => $usuario->id,
                ]);

                SolicitacaoMovimentacao::create([
                    'movimentacao_solicitacao_id' => $solicitacao->solicitacao_id,
                    'movimentacao_usuario_id'     => $usuario->id,
                    'movimentacao_destino_id'     => $novoSetor,
                    'movimentacao_tipo'           => $movTipo,
                    'movimentacao_entregue_para'  => $this->entregue_para,
                    'movimentacao_observacao'     => $this->observacao,
                    'movimentacao_data'           => now(),
                ]);
            }
        });

        flash()->success('Movimentações registradas com sucesso.', [], 'Sucesso!');

        $this->reset([
            'selecionadas',
            'selecionadas_all',
            'status',
            'entregue_para',
            'observacao',
            'setor_destino_id',
        ]);

        $this->resetPage();
        $this->dispatch('refreshMovimentacoes');
    }

    /** 🔧 Determinar tipo de movimentação conforme setor e status */
    private function determinarMovimentacaoTipo(string $status, int $setorOrigem): string
    {
        if ($setorOrigem === 1) { // Recepção
            return match ($status) {
                'aguardando' => 'encaminhamento',
                'entregue', 'devolvido', 'cancelado' => 'entrega',
                default => 'encaminhamento',
            };
        }

        if ($setorOrigem === 2) { // Marcação
            return match ($status) {
                'marcado', 'cancelado', 'devolvido' => 'retorno',
                'agendado', 'aguardando' => 'encaminhamento',
                default => 'encaminhamento',
            };
        }

        return 'encaminhamento';
    }

    /** 🔧 Determinar setor destino conforme regras */
    private function determinarSetorDestino(string $status, ?int $setorOrigem): ?int
    {
        return match ($setorOrigem) {
            1 => match ($status) {
                'aguardando' => 2, // Recepção → Marcação
                'entregue', 'devolvido', 'cancelado' => null, // entregue ao paciente
                default => 1,
            },
            2 => match ($status) {
                'marcado', 'cancelado', 'devolvido' => 1, // volta para Recepção
                default => 2,
            },
            default => null,
        };
    }

    /** 🔎 Query principal (restrita ao setor do usuário) */
    private function solicitacoesQuery()
    {
        $setorUsuario = auth()->user()->setor_id ?? null;

        return Solicitacao::query()
            ->with(['atendimento.paciente', 'procedimento.tipo_procedimento', 'localizacao_atual'])
            ->when($setorUsuario, fn($q) => $q->where('solicitacao_localizacao_atual_id', $setorUsuario))
            ->when($this->filtro_periodo, function (Builder $query) {
                $hoje = Carbon::today();
                return match ($this->filtro_periodo) {
                    'hoje'   => $query->whereDate('solicitacao_data', $hoje),
                    'semana' => $query->whereBetween('solicitacao_data', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                    'mes'    => $query->whereMonth('solicitacao_data', $hoje->month),
                    default  => null,
                };
            })
            ->when($this->filtro_data_inicial, fn($q) => $q->whereDate('solicitacao_data', '>=', $this->filtro_data_inicial))
            ->when($this->filtro_data_final, fn($q) => $q->whereDate('solicitacao_data', '<=', $this->filtro_data_final))
            ->when($this->filtro_tipo_procedimento,
                fn($q) => $q->whereHas('procedimento', fn($p) => $p->where('procedimento_tipo_id', $this->filtro_tipo_procedimento)))
            ->when($this->filtro_procedimento, fn($q) => $q->where('solicitacao_procedimento_id', $this->filtro_procedimento))
            ->when($this->filtro_status, fn($q) => $q->where('solicitacao_status', $this->filtro_status))
            ->when($this->filtro_prioridade,
                fn($q) => $q->whereHas('atendimento', fn($a) => $a->where('atendimento_prioridade', $this->filtro_prioridade)))
            ->when($this->filtro_numero_atendimento,
                fn($q) => $q->whereHas('atendimento', fn($a) => $a->where('atendimento_numero', 'like', "%{$this->filtro_numero_atendimento}%")))
            ->when($this->filtro_nome,
                fn($q) => $q->whereHas('atendimento.paciente', fn($p) => $p->where('paciente_nome', 'like', "%{$this->filtro_nome}%")))
            ->when($this->filtro_cpf,
                fn($q) => $q->whereHas('atendimento.paciente', fn($p) => $p->where('paciente_cpf', 'like', "%{$this->filtro_cpf}%")))
            ->when($this->filtro_sus,
                fn($q) => $q->whereHas('atendimento.paciente', fn($p) => $p->where('paciente_cns', 'like', "%{$this->filtro_sus}%")));
    }

    /** 🎨 Renderização */
    public function render()
    {
        $solicitacoes = $this->solicitacoesQuery()->orderByDesc('solicitacao_data')->paginate(25);

        return view('livewire.admin.movimentacoes.formulario', [
            'solicitacoes'  => $solicitacoes,
            'tipos'         => TipoProcedimento::orderBy('tipo_procedimento_nome')->get(),
            'procedimentos' => Procedimento::orderBy('procedimento_nome')->get(),
            'setores'       => Setor::orderBy('setor_nome')->get(),
        ])->layout('layouts.admin', [
            'title' => 'Movimentações de Solicitações',
        ]);
    }
}
