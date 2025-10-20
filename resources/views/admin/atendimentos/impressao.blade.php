<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Atendimento #{{ formatoId($atendimento->atendimento_id, 3) }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; line-height: 1.4; }
        h1, h2 { margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 20px; }
        .info { margin: 4px 0; }
        .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table, th, td { border: 1px solid #999; }
        th, td { padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-concluido { color: green; font-weight: bold; }
        .status-pendente { color: orange; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Atendimento #{{ formatoId($atendimento->atendimento_id, 3) }}</h1>
        <p>Data: {{ $atendimento->atendimento_data->format('d/m/Y') }}</p>
    </div>

    {{-- Informações do Atendimento --}}
    <div class="section">
        <h2>Informações do Atendimento</h2>
        <p class="info"><span class="label">ID:</span> {{ formatoId($atendimento->atendimento_id, 3) }}</p>
        <p class="info"><span class="label">Prioridade:</span> {{ $atendimento->atendimento_prioridade }}</p>
        <p class="info"><span class="label">Status:</span> 
            @if($atendimento->atendimento_status)
                Concluído
            @else
                Pendente
            @endif
        </p>
        <p class="info"><span class="label">Profissional:</span> {{ $atendimento->profissional->profissional_nome ?? '-' }}</p>
    </div>

    {{-- Informações do Paciente --}}
    <div class="section">
        <h2>Paciente</h2>
        <p class="info"><span class="label">Nome:</span> {{ $atendimento->paciente->paciente_nome }}</p>
        <p class="info"><span class="label">CPF:</span> {{ $atendimento->paciente->paciente_cpf }}</p>
        <p class="info"><span class="label">Cartão SUS:</span> {{ $atendimento->paciente->paciente_cartao_sus }}</p>
        <p class="info"><span class="label">Data de Nascimento:</span> {{ $atendimento->paciente->paciente_data_nascimento->format('d/m/Y') }}</p>
        <p class="info"><span class="label">Nome da Mãe:</span> {{ $atendimento->paciente->paciente_nome_mae ?? '-' }}</p>
    </div>

    {{-- Solicitações --}}
    <div class="section">
        <h2>Solicitações Vinculadas ({{ $atendimento->solicitacoes->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Procedimento</th>
                    <th>Tipo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($atendimento->solicitacoes as $solicitacao)
                    <tr>
                        <td>{{ formatoId($solicitacao->solicitacao_id, 3) }}</td>
                        <td>{{ $solicitacao->procedimento->procedimento_nome ?? '-' }}</td>
                        <td>{{ $solicitacao->procedimento->tipo_procedimento->tipo_nome ?? '-' }}</td>
                        <td>
                            @if($solicitacao->solicitacao_status)
                                <span class="status-concluido">Concluído</span>
                            @else
                                <span class="status-pendente">Pendente</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">Nenhuma solicitação vinculada</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
