<?php

declare(strict_types = 1);

namespace App\Livewire\Admin;

use App\Models\Atendimento;
use App\Models\Paciente;
use App\Models\Solicitacao;
use Livewire\Component;

class Pesquisar extends Component
{
    public function render()
    {
        $tipo  = request()->input('tipo');
        $termo = request()->input('termo');

        $pacientes    = Paciente::query();
        $atendimentos = Atendimento::query();
        $solicitacoes = Solicitacao::query();

        if ($tipo === 'cpf') {
            $pacientes    = $pacientes->where('paciente_cpf', 'like', "%{$termo}%")->get();
            $atendimentos = collect();
            $solicitacoes = collect();
        } elseif ($tipo === 'cns') {
            $pacientes    = $pacientes->where('paciente_cns', 'like', "%{$termo}%")->get();
            $atendimentos = collect();
            $solicitacoes = collect();
        } elseif ($tipo === 'numero_atendimento') {
            $atendimentos = $atendimentos->where('atendimento_numero', 'like', "%{$termo}%")->get();
            $pacientes    = collect();
            $solicitacoes = Solicitacao::with('atendimento')
                ->whereHas('atendimento', function ($q) use ($termo) {
                    $q->where('atendimento_numero', 'like', "%{$termo}%");
                })->get();
        }

        return view('livewire.admin.pesquisar', compact('pacientes', 'atendimentos', 'solicitacoes', 'termo'))
            ->layout('layouts.admin', ['title' => 'Pesquisar']);
    }
}
