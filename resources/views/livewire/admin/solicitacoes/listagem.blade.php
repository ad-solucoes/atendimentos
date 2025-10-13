<div class="space-y-6">
    <!-- Topo: Busca + botão -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <input
            type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="Buscar por nome da solicitação..."
            class="border border-gray-300 rounded-lg px-4 py-2.5 w-full sm:w-1/2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
        />

        <a href="{{ route('admin.solicitacoes.formulario') }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2.5 rounded-lg shadow transition-all duration-200 text-sm sm:text-sm">
           <i class="fa fa-plus"></i> Nova Solicitação
        </a>
    </div>

    <!-- Mensagem de sucesso -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-2.5 rounded-lg text-sm shadow-sm">
            {{ session('message') }}
        </div>
    @endif

    <!-- Tabela principal -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <!-- Cabeçalho (desktop) -->
        <table class="hidden md:table min-w-full">
            <thead>
                <tr class="bg-gray-100 text-left text-sm text-gray-600">
                    <th class="px-2 py-2 text-center font-semibold" width="100">Status</th>
                    <th class="px-2 py-2 font-semibold">Data da Solicitação</th>
                    <th class="px-2 py-2 font-semibold">Atendimento</th>
                    <th class="px-2 py-2 font-semibold">Tipo</th>
                    <th class="px-2 py-2 font-semibold">Procedimento</th>
                    <th class="px-2 py-2 text-center font-semibold" width="140">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($solicitacoes as $solicitacao)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-2 py-1 text-sm font-medium text-center">
                            @if($solicitacao->solicitacao_status)
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inativo</span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-sm font-medium text-gray-800">{{ $solicitacao->solicitacao_data->format('d/m/Y H:i:s') }}</td>
                        <td class="px-2 py-1 text-sm font-medium text-gray-800">{{ $solicitacao->atendimento->atendimento_numero }} | {{ $solicitacao->atendimento->paciente->paciente_nome }}</td>
                        <td class="px-2 py-1 text-sm font-medium text-gray-800">{{ $solicitacao->procedimento->tipo_procedimento->tipo_procedimento_nome }}</td>
                        <td class="px-2 py-1 text-sm font-medium text-gray-800">{{ $solicitacao->procedimento->procedimento_nome }}</td>
                        <td class="px-2 py-1 text-center space-x-0">
                            <!-- Ver -->
                            <a href="{{ route('admin.solicitacoes.detalhes', $solicitacao->solicitacao_id) }}"
                               class="w-7 h-6 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 p-2 transition"
                               title="Ver Solicitação">
                                <i class="fas fa-eye text-xs"></i>
                            </a>

                            <!-- Editar -->
                            <a href="{{ route('admin.solicitacoes.formulario', $solicitacao->solicitacao_id) }}"
                               class="w-7 h-6 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 p-2 transition"
                               title="Editar Solicitação">
                                <i class="fas fa-pen text-xs"></i>
                            </a>

                            <!-- Excluir -->
                            <button type="button"
                                    wire:click="confirmDelete('{{ $solicitacao->solicitacao_id }}')"
                                    class="w-7 h-6 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 p-2 transition"
                                    title="Excluir Solicitação">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-gray-500">Nenhum solicitacao encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Versão MOBILE -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse ($solicitacoes as $solicitacao)
                <div class="p-4 hover:bg-gray-50 transition">
                    <p class="font-semibold text-gray-800 mb-1">{{ $solicitacao->solicitacao_data }}</p>
                    <p class="text-sm text-gray-600">
                        <strong>Status:</strong> {{ $solicitacao->solicitacao_status ?? '—' }}<br>
                    </p>

                    <!-- Botões -->
                    <div class="flex justify-end mt-2 gap-1">
                        <a href="/solicitacoes/{{ $solicitacao->solicitacao_id }}" 
                           class="w-8 h-8 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-full p-2 transition">
                            <i class="fas fa-eye text-xs"></i>
                        </a>

                        <a href="{{ route('admin.solicitacoes.formulario', $solicitacao->solicitacao_id) }}"
                           class="w-8 h-8 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-full p-2 transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>

                        <button type="button" 
                                wire:click="confirmDelete('{{ $solicitacao->solicitacao_id }}')" 
                                class="w-8 h-8 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 rounded-full p-2 transition">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">Nenhum solicitacao encontrado.</p>
            @endforelse
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-4">{{ $solicitacoes->links() }}</div>

    <!-- Modal de confirmação -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" style="margin-top: 0!important">
            <div class="bg-white rounded-lg p-4 w-full max-w-md shadow-lg">
                <h2 class="text-lg font-semibold mb-3">Confirmar exclusão</h2>
                <p class="mb-4 text-gray-700">Tem certeza de que deseja excluir este solicitacao?</p>
                <div class="flex justify-end space-x-2 mt-5">
                    <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 border rounded hover:bg-gray-100 transition text-sm"><i class="fa fa-times fa-fw"></i> Cancelar</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition  text-sm"><i class="fa fa-trash fa-fw"></i> Excluir</button>
                </div>
            </div>
        </div>
    @endif

</div>
