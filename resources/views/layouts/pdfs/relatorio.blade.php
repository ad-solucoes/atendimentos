<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório - {{ ucfirst($tipo) }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f3f3f3; text-align: left; }
    </style>
</head>
<body>
    <h2>Relatório {{ ucfirst($tipo) }}</h2>
    <p>Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}</p>
    <hr>

    @if($tipo === 'geral')
        <ul>
            <li>Total de Atendimentos: {{ $dados['totalAtendimentos'] }}</li>
            <li>Total de Solicitações: {{ $dados['totalSolicitacoes'] }}</li>
            <li>Total de Pacientes: {{ $dados['totalPacientes'] }}</li>
            <li>Total de Agentes: {{ $dados['totalAgentes'] }}</li>
        </ul>
    @else
        <table>
            <thead>
                <tr>
                    @foreach(array_keys((array) ($dados[0] ?? [])) as $col)
                        <th>{{ ucfirst(str_replace('_', ' ', $col)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($dados as $item)
                    <tr>
                        @foreach((array) $item->toArray() as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
