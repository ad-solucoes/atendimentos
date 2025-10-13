<div class="space-y-6">

    {{-- Abas de navegação --}}
    <div class="flex border-b border-gray-200">
        @php
            $abas = [
                'dados' => 'Dados da Solicitação',
                'movimentacoes' => 'Movimentações'
            ];
        @endphp

        @foreach($abas as $key => $label)
            <button wire:click="$set('aba', '{{ $key }}')"
                class="flex-1 text-center pb-2 border-b-2 text-sm sm:text-base transition
                    {{ $aba === $key ? 'border-blue-600 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Conteúdo da aba selecionada --}}
    <div>
        @if ($aba === 'dados')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Número da Solicitação</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->solicitacao_numero }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Data da Solicitação</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->solicitacao_data->format('d/m/Y') }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Status</h3>
                    @php
                        $statusColors = [
                            'aguardando' => 'bg-yellow-400',
                            'agendado' => 'bg-blue-500',
                            'marcado' => 'bg-indigo-500',
                            'entregue' => 'bg-green-500',
                            'cancelado' => 'bg-red-500',
                        ];
                    @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-white text-sm font-semibold {{ $statusColors[$solicitacao->solicitacao_status] ?? 'bg-gray-400' }}">
                        {{ ucfirst($solicitacao->solicitacao_status) }}
                    </span>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Paciente</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->atendimento->paciente->paciente_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Atendimento</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->atendimento->atendimento_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Procedimento</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->procedimento->procedimento_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Localização Atual</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->localizacao_atual->setor_nome ?? '—' }}</p>
                </div>

                <div>
                    <h3 class="text-gray-600 text-sm font-medium">Criado por</h3>
                    <p class="text-gray-800 font-semibold">{{ $solicitacao->creator?->name ?? '—' }}</p>
                </div>
            </div>
        @elseif ($aba === 'movimentacoes')
            <div class="space-y-4">
                @forelse($solicitacao->movimentacoes()->latest()->get() as $mov)
                    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100 flex justify-between items-start">
                        <div>
                            <p class="text-gray-800 font-medium">
                                {{ ucfirst(str_replace('_',' ', $mov->movimentacao_tipo)) }}
                                @if($mov->movimentacao_entregue_para)
                                    - Entregue para: {{ ucfirst(str_replace('_',' ', $mov->movimentacao_entregue_para)) }}
                                @endif
                            </p>
                            <p class="text-gray-500 text-sm">
                                Usuário: {{ $mov->usuario?->name ?? '—' }} <br>
                                Setor destino: {{ $mov->destino?->setor_nome ?? '—' }} <br>
                                Observação: {{ $mov->movimentacao_observacao ?? '—' }}
                            </p>
                        </div>
                        <div class="text-gray-400 text-sm">
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
