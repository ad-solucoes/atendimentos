<?php

namespace App\Livewire\Admin\Relatorios;

use Livewire\Component;
use App\Models\{Atendimento, Solicitacao, AgenteSaude, Paciente};
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class Index extends Component
{
    public $inicio;
    public $fim;
    public $tipo;
    public $view = null;
    public $dados = [];
    public $grafico = [];

    public function mount()
    {
        $this->inicio = now()->startOfMonth()->format('Y-m-d');
        $this->fim = now()->format('Y-m-d');
    }

    public function gerarRelatorio()
    {
        $inicio = Carbon::parse($this->inicio)->startOfDay();
        $fim = Carbon::parse($this->fim)->endOfDay();

        switch ($this->tipo) {
            case 'geral':
                $this->dados = [
                    'totalAtendimentos' => Atendimento::whereBetween('created_at', [$inicio, $fim])->count(),
                    'totalSolicitacoes' => Solicitacao::whereBetween('created_at', [$inicio, $fim])->count(),
                    'totalPacientes' => Paciente::count(),
                    'totalAgentes' => AgenteSaude::count(),
                ];
                $this->view = 'livewire.admin.relatorios.partes.geral';
                break;

            case 'atendimentos':
                $this->dados = Atendimento::with('paciente')
                    ->whereBetween('created_at', [$inicio, $fim])->get();
                $this->view = 'livewire.admin.relatorios.partes.atendimentos';
                break;

            case 'solicitacoes':
                $this->dados = Solicitacao::with('paciente')
                    ->whereBetween('created_at', [$inicio, $fim])->get();
                $this->view = 'livewire.admin.relatorios.partes.solicitacoes';
                break;

            case 'agentes':
                $this->dados = AgenteSaude::withCount('pacientes')->get();
                $this->view = 'livewire.admin.relatorios.partes.agentes';
                break;

            case 'pacientes':
                $this->dados = Paciente::with('agente_saude')->get();
                $this->view = 'livewire.admin.relatorios.partes.pacientes';
                break;

            default:
                $this->view = null;
        }

        $this->gerarGrafico();
    }

    public function gerarGrafico()
    {
        if ($this->tipo === 'atendimentos') {
            $this->grafico = Atendimento::selectRaw('DATE(created_at) as data, COUNT(*) as total')
                ->groupBy('data')
                ->orderBy('data')
                ->get()
                ->map(fn($row) => ['data' => $row->data, 'total' => $row->total])
                ->toArray();
        } else {
            $this->grafico = [];
        }
    }

    public function exportarPDF()
    {
        if (!$this->view) return;

        $pdf = Pdf::loadView('layouts.pdfs.relatorio', [
            'tipo' => $this->tipo,
            'dados' => $this->dados,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->stream();
        }, "relatorio-{$this->tipo}.pdf");
    }

    public function render()
    {
        return view('livewire.admin.relatorios.index')->layout('layouts.admin', ['title' => 'Relatórios']);
    }
}
