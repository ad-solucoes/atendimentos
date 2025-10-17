<div class="mx-auto py-4 space-y-4">
    {{-- Cards de métricas principais --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-yellow-200 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Solicitações</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalSolicitacoes }}</p>
        </div>

        <div class="bg-blue-300 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Pacientes</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalPacientes }}</p>
        </div>

        <div class="bg-indigo-300 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Atendimentos</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalAtendimentos }}</p>
        </div>    

        <div class="bg-green-300 rounded-xl shadow p-4 flex flex-col items-start">
            <h3 class="text-gray-700 text-sm font-semibold">Total de Procedimentos</h3>
            <p class="text-gray-900 text-2xl font-bold">{{ $totalProcedimentos ?? 0 }}</p>
        </div>    
    </div>

    {{-- Cards de métricas secundárias (por status) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
        @foreach(['pendente', 'aguardando','agendado','marcado','entregue'] as $status)
            @php
                $bg = match($status){
                    'pendente'=>'bg-gray-200',
                    'aguardando'=>'bg-yellow-200',
                    'agendado'=>'bg-blue-300',
                    'marcado'=>'bg-indigo-300',
                    'entregue'=>'bg-green-300',
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

    @if(auth()->user()->hasRole('Administrador'))
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="bg-white p-6 rounded-xl shadow text-center col-span-4">
                <h2 class="text-lg font-semibold text-gray-600">Visitas ao Site</h2>
                <p class="text-3xl font-bold text-blue-700 mt-2">{{ $totalVisitas }}</p>
            </div>
            <canvas class="col-span-8" id="graficoVisitas" style="width: 100%; height: 200px"></canvas>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="bg-white p-6 rounded-xl shadow text-center col-span-4">
                <h2 class="text-lg font-semibold text-gray-600">Consultas Realizadas</h2>
                <p class="text-3xl font-bold text-blue-700 mt-2">{{ $totalConsultas }}</p>
            </div>
            <canvas class="col-span-8" id="graficoConsultas" style="width: 100%; height: 200px"></canvas>
        </div>
    @endif
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Utility: safe get canvas context
    const ctxFor = (id) => {
        const el = document.getElementById(id);
        return el ? el.getContext('2d') : null;
    };

    // 1) Doughnut - Solicitações por Status
    (function () {
        const ctxStatus = ctxFor('chartStatus');
        if (!ctxStatus) return;

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
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    })();

    // 2) Bar - Atendimentos por mês
    (function () {
        const ctxAtend = ctxFor('chartAtendimentos');
        if (!ctxAtend) return;

        // meses podem vir em PT-BR já do backend; se não, usamos fallback
        const mesesLabels = {!! json_encode($meses ?? ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']) !!};

        new Chart(ctxAtend, {
            type: 'bar',
            data: {
                labels: mesesLabels,
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
    })();

    // 3) Line - Visitas por Dia
    (function () {
        const ctxVisitas = ctxFor('graficoVisitas');
        if (!ctxVisitas) return;

        // raw labels/values from backend (assume visitsPorDia is a collection or array)
        const visitasRaw = @json($visitasPorDia->toArray() ?? []);
        const visitasLabelsRaw = Object.keys(visitasRaw);
        const visitasValues = Object.values(visitasRaw);

        // format dates to pt-BR if possible
        const visitasLabels = visitasLabelsRaw.map(d => {
            try {
                const dt = new Date(d);
                if (!isNaN(dt)) return dt.toLocaleDateString('pt-BR');
            } catch (e) {}
            return d;
        });

        new Chart(ctxVisitas, {
            type: 'line',
            data: {
                labels: visitasLabels,
                datasets: [{
                    label: 'Visitas por Dia',
                    data: visitasValues,
                    borderColor: 'rgba(37, 99, 235, 1)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.3,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    })();

    // 4) Line - Consultas por Dia (formata datas em pt-BR)
    (function () {
        const ctxConsultas = ctxFor('graficoConsultas');
        if (!ctxConsultas) return;

        const consultasRaw = @json($consultasPorDia->toArray() ?? []);
        const consultasLabelsRaw = Object.keys(consultasRaw);
        const consultasValues = Object.values(consultasRaw);

        const consultasLabels = consultasLabelsRaw.map(d => {
            try {
                const dt = new Date(d);
                if (!isNaN(dt)) {
                    // formato com dia/mês abreviado (ex: 05/mai)
                    return dt.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' });
                }
            } catch (e) {}
            return d;
        });

        const data = {
            labels: consultasLabels,
            datasets: [{
                label: 'Consultas',
                data: consultasValues,
                borderColor: 'rgba(37, 99, 235, 1)',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                borderWidth: 2,
                tension: 0.25,
                fill: true,
                pointBackgroundColor: 'rgba(37, 99, 235, 1)',
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E40AF',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 6,
                    }
                },
                scales: {
                    x: {
                        title: { display: true, text: 'Data', color: '#374151', font: { size: 12 } },
                        ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 10 },
                        grid: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Total de Consultas', color: '#374151', font: { size: 12 } },
                        ticks: { precision: 0 }
                    }
                }
            }
        };

        new Chart(ctxConsultas, config);
    })();
</script>
