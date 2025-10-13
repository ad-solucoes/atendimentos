<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Paciente;
use App\Models\Atendimento;
use App\Models\Solicitacao;

class Dashboard extends Component
{
    public $pacientes;
    public $atendimentos;
    public $solicitacoes;
    public $protocolos;

    public $solicitacoes_aguardadas;
    public $solicitacoes_agendadas;
    public $solicitacoes_marcadas;

    public function mount()
    {
        $this->pacientes = Paciente::count();
        $this->atendimentos = Atendimento::count();
        $this->solicitacoes = Solicitacao::count();

        $this->solicitacoes_aguardadas = Solicitacao::where('solicitacao_status', 'aguardando')->count();
        $this->solicitacoes_agendadas = Solicitacao::where('solicitacao_status', 'em_andamento')->count();
        $this->solicitacoes_marcadas = Solicitacao::where('solicitacao_status', 'marcada')->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }

    // Funções auxiliares para contagem por período
    public function quantitativoPacientes($periodo)
    {
        $query = Paciente::query();
        return $this->quantitativoPeriodo($query, $periodo);
    }

    public function quantitativoAtendimentos($periodo)
    {
        $query = Atendimento::query();
        return $this->quantitativoPeriodo($query, $periodo);
    }

    public function quantitativoSolicitacoes($periodo)
    {
        $query = Solicitacao::query();
        return $this->quantitativoPeriodo($query, $periodo);
    }

    public function quantitativoProtocolos($periodo)
    {
        $query = Protocolo::query();
        return $this->quantitativoPeriodo($query, $periodo);
    }

    private function quantitativoPeriodo($query, $periodo)
    {
        switch ($periodo) {
            case 'hoje':
                return $query->whereDate('created_at', now())->count();
            case 'esta_semana':
                return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
            case 'este_mes':
                return $query->whereMonth('created_at', now()->month)->count();
            case 'este_ano':
                return $query->whereYear('created_at', now()->year)->count();
            default:
                return $query->count();
        }
    }
}
