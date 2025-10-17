<?php

declare(strict_types = 1);

namespace App\Livewire\Admin;

use App\Models\Atendimento;
use App\Models\Paciente;
use Illuminate\Support\Facades\DB;
use App\Models\Procedimento;
use App\Models\Solicitacao;
use Carbon\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Define locale para português
        Carbon::setLocale('pt_BR');

        // Métricas principais
        $totalSolicitacoes  = Solicitacao::count();
        $totalPacientes     = Paciente::count();
        $totalAtendimentos  = Atendimento::count();
        $totalProcedimentos = Procedimento::count();

        // Contagem por status
        $statusCounts = Solicitacao::selectRaw('solicitacao_status, COUNT(*) as total')
            ->groupBy('solicitacao_status')
            ->pluck('total', 'solicitacao_status')
            ->toArray();

        // Últimas solicitações
        $ultimasSolicitacoes = Solicitacao::with(['atendimento.paciente', 'procedimento'])
            ->latest('solicitacao_data')
            ->limit(20)
            ->get();

        // Fluxo de atendimentos por mês (para o gráfico)
        $meses              = collect(range(1, 12))->map(fn ($m) => Carbon::create()->month($m)->translatedFormat('M'));
        $atendimentosPorMes = collect(range(1, 12))->map(function ($m) {
            return Atendimento::whereMonth('created_at', $m)->count();
        });

        if(auth()->user()->hasRole('Administrador')){
            $totalVisitas = DB::table('visitas')->count();

            // Visitas por dia (para gráfico)
            $visitasPorDia = DB::table('visitas')
                ->selectRaw('DATE(visitado_em) as dia, COUNT(*) as total')
                ->groupBy('dia')
                ->orderBy('dia')
                ->pluck('total', 'dia');

            $totalConsultas = DB::table('consultas_log')->count();

            $consultasPorDia = DB::table('consultas_log')
                ->selectRaw('DATE(consultado_em) as dia, COUNT(*) as total')
                ->groupBy('dia')
                ->orderBy('dia')
                ->pluck('total', 'dia');
        }else{
            $totalVisitas = 0;
            $visitasPorDia = [];
            $totalConsultas = 0;
            $consultasPorDia = [];
        }


        return view('livewire.admin.dashboard', compact(
            'totalSolicitacoes',
            'totalPacientes',
            'totalAtendimentos',
            'totalProcedimentos',
            'statusCounts',
            'ultimasSolicitacoes',
            'meses',
            'atendimentosPorMes',
            'totalVisitas',
            'visitasPorDia',
            'totalConsultas',
            'consultasPorDia',
        ))->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}
