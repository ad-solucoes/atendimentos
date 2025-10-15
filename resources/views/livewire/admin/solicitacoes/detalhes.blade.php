<div class="max-w-6xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    {{-- Abas de navegação --}}
    <div class="flex border-b border-gray-200 mb-6">
        @php
            $abas = [
                'dados' => 'Dados da Solicitação',
                'movimentacoes' => 'Movimentações'
            ];
        @endphp

        @foreach($abas as $key => $label)
            <button
                wire:click="$set('aba', '{{ $key }}')"
                class="flex-1 text-center pb-2 border-b-2 text-sm sm:text-base transition
                    {{ $aba === $key
                        ? 'border-blue-700 text-blue-700 font-semibold'
                        : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Conteúdo da aba selecionada --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        @if ($aba === 'dados')
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Número da Solicitação</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->solicitacao_numero }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Data da Solicitação</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->solicitacao_data->format('d/m/Y') }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Status</h3>
                    @php
                        $statusColors = [
                            'aguardando' => 'bg-yellow-400',
                            'agendado'   => 'bg-blue-500',
                            'marcado'    => 'bg-indigo-500',
                            'entregue'   => 'bg-green-500',
                            'cancelado'  => 'bg-red-500',
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 mt-1 rounded-full text-white text-xs font-semibold {{ $statusColors[$solicitacao->solicitacao_status] ?? 'bg-gray-400' }}">
                        {{ ucfirst($solicitacao->solicitacao_status) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Paciente</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->atendimento->paciente->paciente_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Atendimento</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->atendimento->atendimento_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Procedimento</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->procedimento->procedimento_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Localização Atual</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->localizacao_atual->setor_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-semibold">Criado por</h3>
                    <p class="text-gray-900 font-medium">{{ $solicitacao->creator?->name ?? '—' }}</p>
                </div>
            </div>

        @elseif ($aba === 'movimentacoes')
            <div class="space-y-4">
                @forelse($solicitacao->movimentacoes()->latest()->get() as $mov)
                    <div class="border border-gray-100 bg-gray-50 rounded-xl p-4 shadow-sm flex flex-col sm:flex-row justify-between sm:items-center">
                        <div class="flex-1">
                            <p class="text-gray-800 font-semibold">
                                {{ ucfirst(str_replace('_', ' ', $mov->movimentacao_tipo)) }}
                                @if($mov->movimentacao_entregue_para)
                                    — Entregue para: 
                                    <span class="font-medium text-gray-700">
                                        {{ ucfirst(str_replace('_', ' ', $mov->movimentacao_entregue_para)) }}
                                    </span>
                                @endif
                            </p>
                            <p class="text-gray-500 text-sm leading-tight mt-1">
                                <span class="block">Usuário: {{ $mov->usuario?->name ?? '—' }}</span>
                                <span class="block">Setor destino: {{ $mov->destino?->setor_nome ?? '—' }}</span>
                                <span class="block">Observação: {{ $mov->movimentacao_observacao ?: '—' }}</span>
                            </p>
                        </div>
                        <div class="text-gray-400 text-sm mt-2 sm:mt-0 sm:ml-4">
                            {{ $mov->movimentacao_data->format('d/m/Y H:i') }}
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Nenhuma movimentação registrada.</p>
                @endforelse
            </div>
        @endif
    </div>
</div>
