<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Ficha do Paciente - {{ $paciente->paciente_nome }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1, h2, h3 { margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 8px; border-bottom: 1px solid #ccc; padding-bottom: 4px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 4px 8px; vertical-align: top; }
        .atendimentos-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .atendimentos-table th, .atendimentos-table td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 11px; }
        .atendimentos-table th { background-color: #f2f2f2; }
        .total { font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Ficha do Paciente</h1>
        <h3>{{ $paciente->paciente_nome }} (#{{ str_pad($paciente->paciente_id, 3, '0', STR_PAD_LEFT) }})</h3>
    </div>

    <!-- Informações do Paciente -->
    <div class="section">
        <div class="section-title">Informações Cadastrais</div>
        <table class="info-table">
            <tr>
                <td><strong>Nome:</strong></td>
                <td>{{ $paciente->paciente_nome }}</td>
                <td><strong>CPF:</strong></td>
                <td>{{ $paciente->paciente_cpf }}</td>
            </tr>
            <tr>
                <td><strong>Cartão SUS:</strong></td>
                <td>{{ $paciente->paciente_cartao_sus }}</td>
                <td><strong>Data de Nascimento:</strong></td>
                <td>{{ $paciente->paciente_data_nascimento ? $paciente->paciente_data_nascimento->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Nome da Mãe:</strong></td>
                <td colspan="3">{{ $paciente->paciente_nome_mae }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td colspan="3">{{ $paciente->paciente_status ? 'Ativo' : 'Inativo' }}</td>
            </tr>
        </table>
    </div>

    <!-- Histórico de Atendimentos -->
    <div class="section">
        <div class="section-title">Histórico de Atendimentos</div>

        @if($paciente->atendimentos->count() > 0)
            <table class="atendimentos-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Profissional</th>
                        <th>Prioridade</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paciente->atendimentos as $atendimento)
                        <tr>
                            <td>{{ $atendimento->atendimento_id }}</td>
                            <td>{{ $atendimento->atendimento_data->format('d/m/Y') }}</td>
                            <td>{{ $atendimento->profissional->nome ?? '-' }}</td>
                            <td>{{ $atendimento->atendimento_prioridade }}</td>
                            <td>{{ $atendimento->atendimento_observacoes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                Total de atendimentos: {{ $paciente->atendimentos->count() }}
            </div>
        @else
            <p>Este paciente ainda não possui atendimentos registrados.</p>
        @endif
    </div>

</body>
</html>
