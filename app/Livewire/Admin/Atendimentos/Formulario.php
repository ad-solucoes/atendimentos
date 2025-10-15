<?php

declare(strict_types = 1);

namespace App\Livewire\Admin\Atendimentos;

use App\Models\Atendimento;
use App\Models\Paciente;
use App\Models\Procedimento;
use App\Models\Solicitacao;
use App\Models\SolicitacaoMovimentacao;
use App\Models\TipoProcedimento;
use Livewire\Component;

class Formulario extends Component
{
    public $atendimento_id;

    public $atendimento_paciente_id;

    public $atendimento_prioridade = 'Baixa';

    public $atendimento_numero;

    public $atendimento_data;

    public $atendimento_observacao;

    public $atendimento_status = 1;

    // 🔹 Busca dinâmica de paciente
    public $pacienteBusca = '';

    public $pacientesEncontrados = [];

    public $pacienteSelecionadoIndex = 0;

    // 🔹 Solicitações dinâmicas
    public $solicitacoes = [];

    // 🔹 Listas de apoio
    public $tiposProcedimento = [];

    public $procedimentos = [];

    protected function rules()
    {
        return [
            'atendimento_paciente_id'        => 'required|integer|exists:pacientes,paciente_id',
            'atendimento_prioridade'         => 'required|in:Baixa,Média,Alta',
            'atendimento_data'               => 'required|date',
            'atendimento_observacao'         => 'nullable|string|max:500',
            'atendimento_status'             => 'required|boolean',
            'solicitacoes.*.tipo_id'         => 'required|integer|exists:tipos_procedimento,tipo_procedimento_id',
            'solicitacoes.*.procedimento_id' => 'required|integer|exists:procedimentos,procedimento_id',
            'solicitacoes.*.observacao'      => 'nullable|string|max:300',
        ];
    }

    protected $messages = [
        'atendimento_paciente_id.required'        => 'Selecione o paciente.',
        'solicitacoes.*.tipo_id.required'         => 'Selecione o tipo de procedimento.',
        'solicitacoes.*.procedimento_id.required' => 'Selecione o procedimento.',
    ];

    public function mount($id = null)
    {
        $this->tiposProcedimento = TipoProcedimento::orderBy('tipo_procedimento_nome')->get();

        if (request()->paciente_id) {
            $this->selecionarPaciente(request()->paciente_id);
            $this->atendimento_paciente_id = request()->paciente_id;
        }

        if ($id) {
            $doc = Atendimento::with(['solicitacoes.procedimento'])->find($id);

            if ($doc) {
                $this->atendimento_id          = $doc->atendimento_id;
                $this->atendimento_paciente_id = $doc->atendimento_paciente_id;
                $this->atendimento_prioridade  = $doc->atendimento_prioridade;
                $this->atendimento_numero      = $doc->atendimento_numero;
                $this->atendimento_data        = $doc->atendimento_data->format('Y-m-d\TH:i');
                $this->atendimento_observacao  = $doc->atendimento_observacao;
                $this->atendimento_status      = $doc->atendimento_status;

                $this->solicitacoes = $doc->solicitacoes->map(fn ($s) => [
                    'id'              => $s->solicitacao_id,
                    'tipo_id'         => $s->procedimento->procedimento_tipo_id ?? null,
                    'procedimento_id' => $s->solicitacao_procedimento_id,
                    'observacao'      => $s->solicitacao_observacao ?? null,
                ])->toArray();

                // ⚡ Atualizar procedimentos para cada solicitação
                foreach ($this->solicitacoes as $index => $sol) {
                    $this->atualizarProcedimentos($index);
                }

                if ($doc->paciente) {
                    $this->pacienteBusca = "{$doc->paciente->paciente_nome} [CPF nº {$doc->paciente->paciente_cpf}]";
                }
            }
        } else {
            $this->atendimento_data = now()->format('Y-m-d\TH:i');

            $this->solicitacoes = [
                ['tipo_id' => '', 'procedimento_id' => '', 'observacao' => ''],
            ];
        }

        // Carrega lista inicial de procedimentos
        $this->carregarProcedimentos();
    }

    // 🔹 Atualiza lista de procedimentos ao mudar tipo
    public function updatedSolicitacoes($value, $name)
    {
        if (str_contains($name, '.tipo_id')) {
            $this->carregarProcedimentos();
        }
    }

    private function carregarProcedimentos()
    {
        foreach ($this->solicitacoes as $i => $sol) {
            $tipoId                  = $sol['tipo_id'] ?? null;
            $this->procedimentos[$i] = $tipoId
                ? Procedimento::where('procedimento_tipo_id', $tipoId)->orderBy('procedimento_nome')->get()
                : collect();
        }
    }

    // 🔹 Busca dinâmica de paciente
    public function updatedPacienteBusca()
    {
        if (strlen($this->pacienteBusca) > 1) {
            $this->pacientesEncontrados = Paciente::query()
                ->where('paciente_nome', 'like', "%{$this->pacienteBusca}%")
                ->orWhere('paciente_cpf', 'like', "%{$this->pacienteBusca}%")
                ->orWhere('paciente_cns', 'like', "%{$this->pacienteBusca}%")
                ->take(5)
                ->get();
        } else {
            $this->pacientesEncontrados = [];
        }
    }

    public function selecionarPaciente($id)
    {
        $paciente = Paciente::find($id);

        if ($paciente) {
            $this->atendimento_paciente_id = $paciente->paciente_id;
            $this->pacienteBusca           = "{$paciente->paciente_nome} [CPF nº {$paciente->paciente_cpf}]";
            $this->pacientesEncontrados    = [];
        }
    }

    public function navegaLista($direcao)
    {
        if (empty($this->pacientesEncontrados)) {
            return;
        }

        if ($direcao === 'baixo') {
            $this->pacienteSelecionadoIndex = ($this->pacienteSelecionadoIndex + 1) % count($this->pacientesEncontrados);
        } elseif ($direcao === 'cima') {
            $this->pacienteSelecionadoIndex = ($this->pacienteSelecionadoIndex - 1 + count($this->pacientesEncontrados)) % count($this->pacientesEncontrados);
        }
    }

    public function selecionarPorEnter()
    {
        if (isset($this->pacientesEncontrados[$this->pacienteSelecionadoIndex])) {
            $this->selecionarPaciente($this->pacientesEncontrados[$this->pacienteSelecionadoIndex]->paciente_id);
        }
    }

    // 🔹 Manipulação de solicitações
    public function addSolicitacao()
    {
        $this->solicitacoes[] = ['tipo_id' => '', 'procedimento_id' => '', 'observacao' => ''];
        $this->carregarProcedimentos();
    }

    public function removeSolicitacao($index)
    {
        unset($this->solicitacoes[$index], $this->procedimentos[$index]);
        $this->solicitacoes  = array_values($this->solicitacoes);
        $this->procedimentos = array_values($this->procedimentos);
    }

    public function atualizarProcedimentos($index)
    {
        if (! isset($this->solicitacoes[$index]['tipo_id'])) {
            return;
        }

        $tipoId = $this->solicitacoes[$index]['tipo_id'];

        // Busca apenas os procedimentos do tipo selecionado
        $procedimentos = Procedimento::where('procedimento_tipo_id', $tipoId)
            ->orderBy('procedimento_nome')
            ->get();

        // Guarda na solicitação para uso no select
        $this->solicitacoes[$index]['procedimentos_disponiveis'] = $procedimentos;
    }

    // 🔹 Salvar
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

        foreach ($this->solicitacoes as $sol) {
            $novaSolicitacao = Solicitacao::updateOrCreate(
                ['solicitacao_id' => $sol['id'] ?? null],
                [
                    'solicitacao_atendimento_id'  => $atendimento->atendimento_id,
                    'solicitacao_procedimento_id' => $sol['procedimento_id'],
                    'solicitacao_numero'          => isset($sol['id']) && $sol['id']
                        ? ($sol['solicitacao_numero'] ?? 'S' . rand(100000, 999999))
                        : 'S' . rand(100000, 999999),
                    'solicitacao_data'   => now(),
                    'solicitacao_status' => 'aguardando',
                    'created_user_id'    => auth()->id(),
                    'updated_user_id'    => auth()->id(),
                ]
            );

            if (empty($sol['id'])) {
                SolicitacaoMovimentacao::create([
                    'movimentacao_solicitacao_id' => $novaSolicitacao->solicitacao_id,
                    'movimentacao_usuario_id'     => auth()->id(),
                    'movimentacao_tipo'           => 'encaminhamento',
                    'movimentacao_observacao'     => 'Solicitação criada no atendimento.',
                ]);
            }
        }

        flash()->success('Atendimento salvo com sucesso.', [], 'Sucesso!');

        return redirect()->route('admin.atendimentos.detalhes', $atendimento->atendimento_id);
    }

    private function gerarNumeroAtendimento(): string
    {
        $ano   = now()->format('Y');
        $count = Atendimento::whereYear('created_at', $ano)->count() + 1;

        return $ano . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.admin.atendimentos.formulario')
            ->layout('layouts.admin', [
                'title' => $this->atendimento_id == null ? 'Cadastrar Atendimento' : 'Editar Atendimento',
            ]);
    }
}
