<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use App\Models\Procedimento;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Formulario extends Component
{
    public $atendimento_id;
    public $atendimento_paciente_id;
    public $atendimento_prioridade = 'Baixa';
    public $atendimento_numero;
    public $atendimento_data;
    public $atendimento_observacao;
    public $atendimento_status = 1;

    // Solicitacoes dinâmicas
    public $solicitacoes = [];

    protected function rules()
    {
        return [
            'atendimento_paciente_id' => 'required|integer|exists:pacientes,paciente_id',
            'atendimento_prioridade'  => 'required|in:Baixa,Média,Alta',
            'atendimento_data'        => 'required|date',
            'atendimento_observacao'  => 'nullable|string|max:500',
            'atendimento_status'      => 'required|boolean',

            'solicitacoes.*.procedimento_id' => 'required|integer|exists:procedimentos,procedimento_id',
            'solicitacoes.*.observacao'      => 'nullable|string|max:300',
        ];
    }

    protected $messages = [
        'atendimento_paciente_id.required' => 'Selecione o paciente.',
        'solicitacoes.*.procedimento_id.required' => 'Selecione o procedimento.',
    ];

    public function mount($id = null)
    {
        $this->atendimento_data = now()->format('Y-m-d\TH:i');

        if ($id) {
            $doc = Atendimento::with('solicitacoes')->find($id);

            if ($doc) {
                $this->atendimento_id          = $doc->atendimento_id;
                $this->atendimento_paciente_id = $doc->atendimento_paciente_id;
                $this->atendimento_prioridade  = $doc->atendimento_prioridade;
                $this->atendimento_numero      = $doc->atendimento_numero;
                $this->atendimento_data        = $doc->atendimento_data;
                $this->atendimento_observacao  = $doc->atendimento_observacao;
                $this->atendimento_status      = $doc->atendimento_status;

                $this->solicitacoes = $doc->solicitacoes->map(fn($s) => [
                    'id' => $s->solicitacao_id,
                    'procedimento_id' => $s->solicitacao_procedimento_id,
                    'observacao' => $s->solicitacao_observacao,
                ])->toArray();
            }
        } else {
            $this->solicitacoes = [
                ['procedimento_id' => '', 'observacao' => '']
            ];
        }
    }

    public function addSolicitacao()
    {
        $this->solicitacoes[] = ['procedimento_id' => '', 'observacao' => ''];
    }

    public function removeSolicitacao($index)
    {
        unset($this->solicitacoes[$index]);
        $this->solicitacoes = array_values($this->solicitacoes);
    }

    public function save()
    {
        $this->validate();

        $atendimento = Atendimento::updateOrCreate(
            ['atendimento_id' => $this->atendimento_id],
            [
                'atendimento_paciente_id' => $this->atendimento_paciente_id,
                'atendimento_prioridade'  => $this->atendimento_prioridade,
                'atendimento_numero'      => $this->atendimento_numero ?? $this->gerarNumeroAtendimento(),
                'atendimento_data'        => $this->atendimento_data,
                'atendimento_observacao'  => $this->atendimento_observacao,
                'atendimento_status'      => $this->atendimento_status,
                'created_user_id'         => auth()->id(),
                'updated_user_id'         => auth()->id(),
            ]
        );

        // Salvar solicitações
        foreach ($this->solicitacoes as $sol) {
            $novaSolicitacao = Solicitacao::updateOrCreate(
                [
                    'solicitacao_id' => $sol['id'] ?? null,
                ],
                [
                    'solicitacao_atendimento_id' => $atendimento->atendimento_id,
                    'solicitacao_procedimento_id' => $sol['procedimento_id'],
                    'solicitacao_numero' => 'S' . rand(100000, 999999),
                    'solicitacao_data' => now(),
                    'solicitacao_status' => 'aguardando',
                    'created_user_id' => auth()->id(),
                    'updated_user_id' => auth()->id(),
                ]
            );

            // Criar movimentação inicial se for nova
            if (empty($sol['id'])) {
                SolicitacaoMovimentacao::create([
                    'movimentacao_solicitacao_id' => $novaSolicitacao->solicitacao_id,
                    'movimentacao_usuario_id' => auth()->id(),
                    'movimentacao_tipo' => 'encaminhamento',
                    'movimentacao_observacao' => 'Solicitação criada no atendimento',
                ]);
            }
        }

        session()->flash('message', 'Atendimento salvo com sucesso!');
        return redirect()->route('admin.atendimentos.listagem');
    }

    private function gerarNumeroAtendimento(): string
    {
        $ano = now()->format('Y');
        $count = Atendimento::whereYear('created_at', $ano)->count() + 1;
        return $ano . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $procedimentos = Procedimento::orderBy('procedimento_nome')->get();

        return view('livewire.admin.atendimentos.formulario', compact('procedimentos'))
            ->layout('layouts.admin', [
                'title' => $this->atendimento_id == null ? 'Cadastrar Atendimento' : 'Editar Atendimento',
            ]);
    }
}
