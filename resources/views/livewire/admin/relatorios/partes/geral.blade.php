<div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-semibold mb-4">Resumo Gerencial</h3>
    <ul class="space-y-2">
        <li><strong>Total de Atendimentos:</strong> {{ $dados['totalAtendimentos'] }}</li>
        <li><strong>Total de Solicitações:</strong> {{ $dados['totalSolicitacoes'] }}</li>
        <li><strong>Total de Pacientes:</strong> {{ $dados['totalPacientes'] }}</li>
        <li><strong>Total de Agentes:</strong> {{ $dados['totalAgentes'] }}</li>
    </ul>
</div>
