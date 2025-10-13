<div class="space-y-10">

    <!-- Cards principais -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        @php
            $cards = [
                ['label' => 'Pacientes', 'emoji' => '👥', 'count' => $pacientes, 'color' => 'bg-blue-600', 'route' => route('admin.pacientes.listagem'), 'quantitativos' => [
                    'Hoje' => $this->quantitativoPacientes('hoje'),
                    'Esta Semana' => $this->quantitativoPacientes('esta_semana'),
                    'Este Mês' => $this->quantitativoPacientes('este_mes'),
                    'Este Ano' => $this->quantitativoPacientes('este_ano'),
                ]],
                ['label' => 'Atendimentos', 'emoji' => '📅', 'count' => $atendimentos, 'color' => 'bg-green-600', 'route' => route('admin.atendimentos.listagem'), 'quantitativos' => [
                    'Hoje' => $this->quantitativoAtendimentos('hoje'),
                    'Esta Semana' => $this->quantitativoAtendimentos('esta_semana'),
                    'Este Mês' => $this->quantitativoAtendimentos('este_mes'),
                    'Este Ano' => $this->quantitativoAtendimentos('este_ano'),
                ]],
                ['label' => 'Solicitações', 'emoji' => '📝', 'count' => $solicitacoes, 'color' => 'bg-yellow-500', 'route' => route('admin.solicitacoes.listagem'), 'quantitativos' => [
                    'Hoje' => $this->quantitativoSolicitacoes('hoje'),
                    'Esta Semana' => $this->quantitativoSolicitacoes('esta_semana'),
                    'Este Mês' => $this->quantitativoSolicitacoes('este_mes'),
                    'Este Ano' => $this->quantitativoSolicitacoes('este_ano'),
                ]],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="text-3xl sm:text-4xl mb-1 sm:mb-2">{{ $card['emoji'] }}</div>
                <h3 class="text-base sm:text-lg font-semibold text-gray-700 mb-1">{{ $card['label'] }}</h3>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $card['count'] }}</p>
                <ul class="mt-2 text-left text-sm text-gray-600">
                    @foreach ($card['quantitativos'] as $periodo => $valor)
                        <li><small>{{ $periodo }}:</small> <b>{{ $valor }}</b></li>
                    @endforeach
                </ul>
                <div class="mt-2 h-1.5 w-10 sm:w-12 rounded-full {{ $card['color'] }}"></div>
                <a href="{{ $card['route'] }}" class="mt-2 text-blue-700 hover:text-blue-900 text-sm sm:text-base transition">Mais informações →</a>
            </div>
        @endforeach
    </div>

    <!-- Cards de solicitações por status -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mt-6">
        @php
            $statusCards = [
                ['label' => 'Aguardadas', 'count' => $solicitacoes_aguardadas, 'color' => 'bg-gray-800', 'status' => 'aguardando'],
                ['label' => 'Agendadas', 'count' => $solicitacoes_agendadas, 'color' => 'bg-gray-800', 'status' => 'em_andamento'],
                ['label' => 'Marcadas', 'count' => $solicitacoes_marcadas, 'color' => 'bg-gray-800', 'status' => 'marcada'],
            ];
        @endphp

        @foreach ($statusCards as $sCard)
            <div class="bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 flex flex-col items-center justify-center text-center">
                <h3 class="text-2xl font-bold text-white mb-1 {{ $sCard['color'] }}">{{ $sCard['count'] }}</h3>
                <p class="text-base sm:text-lg text-gray-700 mb-2">Solicitações {{ strtoupper($sCard['label']) }}</p>
                <form action="#" method="post" target="_blank">
                {{-- <form action="{{ route('admin.consultas.impressao', ['consulta' => 'solicitacoes', 'tipo' => 'listagem_solicitacoes']) }}" method="post" target="_blank"> --}}
                    @csrf
                    <input type="hidden" name="status" value="{{ $sCard['status'] }}">
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm sm:text-base transition-all duration-300">
                        <i class="fas fa-print fa-fw"></i> Imprimir relação
                    </button>
                </form>
            </div>
        @endforeach
    </div>

</div>
