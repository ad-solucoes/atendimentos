<div class="space-y-6">
    <!-- Topo: Busca + botão -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <input
            type="text"
            wire:model.live.debounce.500ms="search"
            placeholder="🔍 Buscar por título, número..."
            class="border border-gray-300 rounded-lg px-4 py-2.5 w-full sm:w-1/2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
        />

        <a href="{{ route('admin.agentes_saude.formulario') }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2.5 rounded-lg shadow transition-all duration-200 text-sm sm:text-base">
           <i class="fa-solid fa-plus"></i> Novo Agente de Saúde
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
                <tr class="bg-gray-100 text-left text-sm uppercase text-gray-600">
                    <th class="px-4 py-3">Nome do Agente de Saúde</th>
                    <th class="px-4 py-3">Equipe de Saúde</th>
                    <th class="px-4 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($agentes_saude as $agente_saude)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $agente_saude->agente_saude_nome }}</td>
                        <td class="px-4 py-2 font-medium text-gray-800">{{ $agente_saude->equipe_saude->equipe_saude_nome }}</td>
                        <td class="px-4 py-2 text-right space-x-0">
                            <!-- Ver -->
                            <a href="/agentes_saude/{{ $agente_saude->agente_saude_id }}"
                               class="w-9 h-9 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-full p-2 transition"
                               title="Ver Agente de Saúde">
                                <i class="fas fa-eye text-xs"></i>
                            </a>

                            <!-- Editar -->
                            <a href="{{ route('admin.agentes_saude.formulario', $agente_saude->agente_saude_id) }}"
                               class="w-9 h-9 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-full p-2 transition"
                               title="Editar Agente de Saúde">
                                <i class="fas fa-pen text-xs"></i>
                            </a>

                            <!-- Excluir -->
                            <button type="button"
                                    wire:click="confirmDelete('{{ $agente_saude->agente_saude_id }}')"
                                    class="w-9 h-9 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 rounded-full p-2 transition"
                                    title="Excluir Agente de Saúde">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4 text-gray-500">Nenhum agente_saude encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>

        <!-- Versão MOBILE -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse ($agentes_saude as $agente_saude)
                <div class="p-4 hover:bg-gray-50 transition">
                    <p class="font-semibold text-gray-800 mb-1">{{ $agente_saude->agente_saude_nome }}</p>
                    <p class="text-sm text-gray-600">
                        <strong>Status:</strong> {{ $agente_saude->agente_saude_status ?? '—' }}<br>
                    </p>

                    <!-- Botões -->
                    <div class="flex justify-end mt-2 gap-1">
                        <a href="/agentes_saude/{{ $agente_saude->agente_saude_id }}" 
                           class="w-9 h-9 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-full p-2 transition">
                            <i class="fas fa-eye text-xs"></i>
                        </a>

                        <a href="{{ route('admin.agentes_saude.formulario', $agente_saude->agente_saude_id) }}"
                           class="w-9 h-9 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-full p-2 transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>

                        <button type="button" 
                                wire:click="confirmDelete('{{ $agente_saude->agente_saude_id }}')" 
                                class="w-9 h-9 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 rounded-full p-2 transition">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">Nenhum agente_saude encontrado.</p>
            @endforelse
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-4">{{ $agentes_saude->links() }}</div>

    <!-- Modal de confirmação -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
                <h2 class="text-lg font-semibold mb-3">Confirmar exclusão</h2>
                <p class="mb-4 text-gray-700">Tem certeza de que deseja excluir este agente_saude?</p>
                <div class="flex justify-end space-x-3">
                    <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 border rounded hover:bg-gray-100 transition">Cancelar</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">Excluir</button>
                </div>
            </div>
        </div>
    @endif

</div>
