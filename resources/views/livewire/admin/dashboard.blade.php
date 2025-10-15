<div class="mx-auto py-4 space-y-4">
    {{-- Cards de métricas principais --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-yellow-100 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Solicitações</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalSolicitacoes }}</p>
        </div>

        <div class="bg-blue-100 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Pacientes</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalPacientes }}</p>
        </div>

        <div class="bg-indigo-100 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Atendimentos</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalAtendimentos }}</p>
        </div>    

        <div class="bg-green-100 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Procedimentos</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalProcedimentos ?? 0 }}</p>
        </div>    
    </div>

    {{-- Cards de métricas secundárias (por status) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
        @foreach(['aguardando','agendado','marcado','entregue','cancelado'] as $status)
            @php
                $bg = match($status){
                    'aguardando'=>'bg-yellow-200',
                    'agendado'=>'bg-blue-200',
                    'marcado'=>'bg-indigo-200',
                    'entregue'=>'bg-green-200',
                    'cancelado'=>'bg-red-200',
                    default=>'bg-gray-200'
                };
            @endphp
            <div class="{{ $bg }} rounded-xl shadow p-3 flex flex-col items-start">
                <h4 class="text-gray-700 text-sm font-semibold">{{ ucfirst($status) }}</h4>
                <p class="text-gray-900 text-xl font-bold">{{ $statusCounts[$status] ?? 0 }}</p>
            </div>
        @endforeach
    </div>

    {{-- Gráficos --}}
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        {{-- Gráfico de Atendimentos --}}
        <div class="bg-white rounded-xl shadow p-6 col-span-8">
            <h3 class="text-gray-700 text-lg font-semibold mb-4">Fluxo de Atendimentos por Mês</h3>
            <canvas id="chartAtendimentos" class="h-64 w-full"></canvas>
        </div>

        {{-- Gráfico de Solicitações por Status --}}
        <div class="bg-white rounded-xl shadow p-6 col-span-4">
            <h3 class="text-gray-700 text-lg font-semibold mb-4">Solicitações por Status</h3>
            <canvas id="chartStatus" class="h-64 w-full"></canvas>
        </div>
    </div>

    {{-- Últimas Solicitações --}}
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-gray-700 text-lg font-semibold mb-4">Últimas Solicitações (20 últimos)</h3>
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="p-2 text-left">Número</th>
                    <th class="p-2 text-left">Paciente</th>
                    <th class="p-2 text-left">Procedimento</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasSolicitacoes as $s)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">{{ $s->solicitacao_numero }}</td>
                        <td class="p-2">{{ $s->atendimento->paciente->paciente_nome ?? '—' }}</td>
                        <td class="p-2">{{ $s->procedimento->procedimento_nome ?? '—' }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 rounded-full text-white text-xs font-semibold
                                {{ match($s->solicitacao_status){
                                    'aguardando' => 'bg-yellow-400',
                                    'agendado' => 'bg-blue-500',
                                    'marcado' => 'bg-indigo-500',
                                    'entregue' => 'bg-green-500',
                                    'cancelado' => 'bg-red-500',
                                    default => 'bg-gray-400',
                                } }}">
                                {{ ucfirst($s->solicitacao_status) }}
                            </span>
                        </td>
                        <td class="p-2">{{ $s->solicitacao_data->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Nenhuma solicitação encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de Solicitações por Status
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Aguardando','Agendado','Marcado','Entregue','Cancelado'],
            datasets: [{
                label: 'Solicitações',
                data: [
                    {{ $statusCounts['aguardando'] ?? 0 }},
                    {{ $statusCounts['agendado'] ?? 0 }},
                    {{ $statusCounts['marcado'] ?? 0 }},
                    {{ $statusCounts['entregue'] ?? 0 }},
                    {{ $statusCounts['cancelado'] ?? 0 }}
                ],
                backgroundColor: ['#facc15','#3b82f6','#6366f1','#22c55e','#ef4444'],
                borderWidth: 1
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    // Gráfico de Atendimentos (exemplo de dados)
    const ctxAtend = document.getElementById('chartAtendimentos').getContext('2d');
    new Chart(ctxAtend, {
        type: 'bar',
        data: {
            labels: {!! json_encode($meses ?? ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']) !!},
            datasets: [{
                label: 'Atendimentos',
                data: {!! json_encode($atendimentosPorMes ?? array_fill(0,12,0)) !!},
                backgroundColor: '#3b82f6'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
