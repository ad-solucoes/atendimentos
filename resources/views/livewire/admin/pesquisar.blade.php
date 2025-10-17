<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <h2 class="text-lg font-semibold text-gray-700">
        Resultado da Pesquisa para: <span class="text-blue-600">{{ $termo }}</span>
    </h2>

    {{-- Pacientes --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <h3 class="text-gray-600 font-semibold mb-2">Pacientes ({{ $pacientes->count() }})</h3>

        @if ($pacientes->isEmpty())
            <p class="text-gray-500 text-sm">Nenhum paciente encontrado.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 border-b">#ID</th>
                            <th class="px-3 py-2 border-b">Nome</th>
                            <th class="px-3 py-2 border-b">CPF</th>
                            <th class="px-3 py-2 border-b">Cartão SUS</th>
                            <th class="px-3 py-2 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pacientes as $paciente)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">{{ formatoId($paciente->paciente_id, 3) }}</td>
                                <td class="px-3 py-2">{{ $paciente->paciente_nome }}</td>
                                <td class="px-3 py-2">{{ $paciente->paciente_cpf }}</td>
                                <td class="px-3 py-2">{{ $paciente->paciente_cns }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.pacientes.detalhes', $paciente->paciente_id) }}"
                                        class="text-blue-600 hover:underline text-sm">Exibir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Atendimentos --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <h3 class="text-gray-600 font-semibold mb-2">Atendimentos ({{ $atendimentos->count() }})</h3>

        @if ($atendimentos->isEmpty())
            <p class="text-gray-500 text-sm">Nenhum atendimento encontrado.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 border-b">#ID</th>
                            <th class="px-3 py-2 border-b">Paciente</th>
                            <th class="px-3 py-2 border-b">Data</th>
                            <th class="px-3 py-2 border-b">Prioridade</th>
                            <th class="px-3 py-2 border-b">Status</th>
                            <th class="px-3 py-2 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($atendimentos as $atendimento)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">{{ formatoId($atendimento->atendimento_id, 3) }}</td>
                                <td class="px-3 py-2">{{ $atendimento->paciente->paciente_nome ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $atendimento->atendimento_data->format('d/m/Y') }}</td>
                                <td class="px-3 py-2">{{ $atendimento->atendimento_prioridade }}</td>
                                <td class="px-3 py-2">
                                    @if ($atendimento->atendimento_status)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Concluído</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.atendimentos.detalhes', $atendimento->atendimento_id) }}"
                                        class="text-blue-600 hover:underline text-sm">Exibir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Solicitações --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
        <h3 class="text-gray-600 font-semibold mb-2">Solicitações ({{ $solicitacoes->count() }})</h3>

        @if ($solicitacoes->isEmpty())
            <p class="text-gray-500 text-sm">Nenhuma solicitação encontrada.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-3 py-2 border-b">#ID</th>
                            <th class="px-3 py-2 border-b">Atendimento</th>
                            <th class="px-3 py-2 border-b">Procedimento</th>
                            <th class="px-3 py-2 border-b">Tipo</th>
                            <th class="px-3 py-2 border-b">Status</th>
                            <th class="px-3 py-2 border-b">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($solicitacoes as $solicitacao)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2">{{ formatoId($solicitacao->solicitacao_id, 3) }}</td>
                                <td class="px-3 py-2">{{ $solicitacao->atendimento->atendimento_numero ?? '-' }}</td>
                                <td class="px-3 py-2">{{ $solicitacao->procedimento->procedimento_nome ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    {{ $solicitacao->procedimento->tipo_procedimento->tipo_nome ?? '-' }}</td>
                                <td class="px-3 py-2">
                                    @if ($solicitacao->solicitacao_status)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Concluído</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.solicitacoes.detalhes', $solicitacao->solicitacao_id) }}"
                                        class="text-blue-600 hover:underline text-sm">Exibir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
