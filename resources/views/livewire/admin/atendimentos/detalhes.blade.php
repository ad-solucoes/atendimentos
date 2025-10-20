<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Informações do Atendimento --}}
    <div class="space-y-4 bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">Detalhes do Atendimento</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <h3 class="text-gray-400 text-sm font-medium">#ID</h3>
                <p class="text-gray-800 font-semibold">{{ formatoId($atendimento->atendimento_id, 3) }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Data</h3>
                <p class="text-gray-800 font-semibold">{{ $atendimento->atendimento_data->format('d/m/Y') }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Prioridade</h3>
                <p class="text-gray-800 font-semibold">
                    @if ($atendimento->atendimento_prioridade == 'Baixa')
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Baixa</span>
                    @elseif ($atendimento->atendimento_prioridade == 'Média')
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Média</span>
                    @elseif ($atendimento->atendimento_prioridade == 'Alta')
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Alta</span>
                    @endif
                </p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Status</h3>
                @if($atendimento->solicitacoes)
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Concluído</span>
                @else
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendente</span>
                @endif
            </div>
        </div>

        {{-- Paciente vinculado --}}
        <div class="mt-4 border-t border-gray-200 pt-4">
            <h3 class="text-gray-600 font-semibold mb-2">Paciente</h3>
            <p class="text-sm text-gray-700">
                <strong>Nome:</strong> {{ $atendimento->paciente->paciente_nome }} <br>
                <strong>CPF:</strong> {{ $atendimento->paciente->paciente_cpf }} <br>
                <strong>Cartão SUS:</strong> {{ $atendimento->paciente->paciente_cartao_sus }} <br>
                <strong>Data de Nascimento:</strong> {{ $atendimento->paciente->paciente_data_nascimento->format('d/m/Y') }}
            </p>
        </div>

        {{-- Solicitações vinculadas --}}
        <div class="mt-6 border-t border-gray-200 pt-4">
            <h3 class="text-gray-600 font-semibold mb-2 flex justify-between items-center">
                Solicitações
                <span class="text-sm text-gray-500">{{ $atendimento->solicitacoes->count() }} registro(s)</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 border-b">#ID</th>
                            <th class="px-3 py-2 border-b">Procedimento</th>
                            <th class="px-3 py-2 border-b">Tipo</th>
                            <th class="px-3 py-2 border-b">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($atendimento->solicitacoes as $solicitacao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">{{ formatoId($solicitacao->solicitacao_id, 3) }}</td>
                                <td class="px-3 py-2">{{ $solicitacao->procedimento->procedimento_nome ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $solicitacao->procedimento->tipo_procedimento->tipo_nome ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @if($solicitacao->solicitacao_status)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Concluído</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-gray-500">
                                    Nenhuma solicitação vinculada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Botões --}}
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:justify-end sm:space-x-2 space-y-2 sm:space-y-0">
            <a href="{{ route('admin.atendimentos.listagem') }}" class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm hover:bg-gray-100 transition">
                <i class="fa fa-times fa-fw"></i> Voltar
            </a>

            <a href="{{ route('admin.atendimentos.impressao', ['id' => $atendimento->atendimento_id]) }}" 
               target="_blank"
               class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded shadow w-full sm:w-auto text-center text-sm transition flex items-center justify-center gap-2">
                <i class="fa fa-file-pdf"></i> Imprimir Atendimento
            </a>

            <a href="{{ route('admin.atendimentos.enviar-contato', $atendimento->atendimento_id) }}" 
                class="btn btn-success" target="_blank">
                Enviar informações ao paciente
            </a>
        </div>

    </div>
</div>
