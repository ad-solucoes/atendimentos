<div class="space-y-6">
    <!-- Filtro e Ações -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-4">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            {{-- Registros por página --}}
            <div class="flex flex-col">
                <label for="perPage" class="text-sm font-semibold text-gray-700 mb-1">
                    Registros por página:
                </label>
                <select id="perPage" wire:model.live="perPage"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-36">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            {{-- Campo de busca --}}
            <div class="flex flex-col md:flex-1">
                <label for="searchTerm" class="text-sm font-semibold text-gray-700 mb-1">
                    Buscar:
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa fa-search"></i>
                    </span>

                    <input id="searchTerm" type="text" wire:model.live.debounce.500ms="searchTerm"
                        placeholder="Buscar por nome do setor..." autocomplete="off"
                        class="pl-9 pr-9 w-full border border-gray-300 rounded-lg py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition" />

                    @if ($searchTerm)
                        <button type="button" wire:click="$set('searchTerm', '')" title="Limpar busca"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition">
                            <i class="fa fa-times"></i>
                        </button>
                    @endif
                </div>
            </div>

            {{-- Total e botão de ação --}}
            <div class="flex flex-col md:items-end">
                <label class="text-sm font-semibold text-gray-700 mb-1">&nbsp;</label>
                <div class="flex items-center justify-between md:justify-end gap-3">

                    <small class="text-gray-600 text-sm">
                        <i class="fa fa-info-circle text-gray-400"></i>
                        Total: <span class="font-semibold text-gray-800">{{ $tipos_procedimento->total() }}</span>
                        registro(s)
                    </small>

                    @can('Adicionar Tipo de Procedimento')
                        <a href="{{ route('admin.tipos_procedimento.formulario') }}"
                            class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg shadow transition-all duration-200 text-sm">
                            <i class="fa fa-plus"></i> Novo Tipo de Procedimento
                        </a>
                    @else
                        <button type="button"
                        class="inline-flex items-center justify-center gap-2 bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition-all duration-200 text-sm opacity-50 cursor-not-allowed">
                            <i class="fa fa-plus"></i> Novo Tipo de Procedimento
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela principal -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <!-- Cabeçalho (desktop) -->
        <table class="hidden md:table min-w-full">
            <thead>
                <tr class="bg-gray-100 text-left text-sm text-gray-600">
                    <th class="px-2 py-2 text-center font-semibold" width="100"
                        wire:click="sortByField('tipo_procedimento_status')" style="cursor: pointer;"
                        title="Clique para ordenar">
                        Status
                        @include('livewire.partials._sort-icon', ['field' => 'tipo_procedimento_status'])
                    </th>
                    <th class="px-2 py-2 font-semibold" wire:click="sortByField('tipo_procedimento_nome')"
                        style="cursor: pointer;" title="Clique para ordenar">
                        Nome do Tipo de Procedimento
                        @include('livewire.partials._sort-icon', ['field' => 'tipo_procedimento_nome'])
                    </th>
                    <th class="px-2 py-2 text-center font-semibold" width="140">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr wire:loading.class.remove="hidden" wire:target="searchTerm" class="hidden">
                    <td colspan="4" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-1.5 text-gray-600">
                            {{-- Spinner visível e animado --}}
                            <div class="relative flex items-center justify-center">
                                <div class="w-10 h-10 border-4 border-gray-300 rounded-full"></div>
                                <div
                                    class="absolute w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin">
                                </div>
                            </div>
                            {{-- Mensagem --}}
                            <div>
                                <p class="font-semibold text-gray-700">Carregando registros...</p>
                                <p class="text-sm text-gray-500">Aguarde um momento</p>
                            </div>

                        </div>
                    </td>
                </tr>
                @forelse ($tipos_procedimento as $tipo_procedimento)
                    <tr class="border-t hover:bg-gray-50 transition" wire:loading.remove wire:target="searchTerm">
                        <td class="px-2 py-1 text-sm font-medium text-center">
                            @if ($tipo_procedimento->tipo_procedimento_status)
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inativo</span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-sm font-medium text-gray-800">
                            {{ $tipo_procedimento->tipo_procedimento_nome }}</td>
                        <td class="px-2 py-1 text-center space-x-0">
                            <!-- Exibir -->
                            @can('Exibir Tipo de Procedimento')
                            <a href="{{ route('admin.tipos_procedimento.detalhes', $tipo_procedimento->tipo_procedimento_id) }}"
                                class="w-7 h-6 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 p-2 transition"
                                title="Exibir Tipo de Procedimento">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            @else
                                <button type="button"
                                class="w-7 h-6 inline-flex items-center justify-center bg-blue-100 text-blue-700 p-2 transition opacity-60 cursor-not-allowed"
                                title="Exibir Tipo de Procedimento">
                                    <i class="fas fa-eye text-xs"></i>
                                </button>
                            @endif

                            <!-- Editar -->
                            @can('Editar Tipo de Procedimento')
                            <a href="{{ route('admin.tipos_procedimento.formulario', $tipo_procedimento->tipo_procedimento_id) }}"
                                class="w-7 h-6 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 p-2 transition"
                                title="Editar Tipo de Procedimento">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            @else
                                <button type="button"
                                class="w-7 h-6 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 p-2 transition opacity-60 cursor-not-allowed"
                                title="Editar Tipo de Procedimento">
                                    <i class="fas fa-pen text-xs"></i>
                                </button>
                            @endif

                            <!-- Deletar -->
                            @can('Deletar Tipo de Procedimento')
                            <button type="button"
                                wire:click="confirmDelete('{{ $tipo_procedimento->tipo_procedimento_id }}')"
                                class="w-7 h-6 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 p-2 transition"
                                title="Deletar Tipo de Procedimento">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                            @else
                                <button type="button"
                                class="w-7 h-6 inline-flex items-center justify-center bg-red-100 text-red-700 p-2 transition opacity-60 cursor-not-allowed"
                                title="Deletar Tipo de Procedimento">
                                    <i class="fas fa-trash-alt text-xs"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove wire:target="searchTerm">
                        <td colspan="4" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-1.5">

                                <div class="text-gray-400">
                                    <i class="fa fa-exclamation-triangle text-4xl"></i>
                                </div>

                                <div>
                                    @if ($searchTerm)
                                        <h4 class="text-gray-700 font-semibold text-lg">
                                            Nenhum tipo de procedimento encontrado
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Tente buscar por outros termos ou
                                            <button wire:click="$set('searchTerm', '')"
                                                class="text-blue-600 hover:underline font-medium">
                                                limpe o filtro
                                            </button>.
                                        </p>
                                    @else
                                        <h4 class="text-gray-700 font-semibold text-lg">
                                            Nenhum tipo de procedimento cadastrado
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Comece criando um novo tipo de procedimento.
                                        </p>
                                    @endif
                                </div>

                                @if (!$searchTerm)
                                    <a href="{{ route('admin.tipos_procedimento.formulario') }}"
                                        class="mt-3 inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow transition-all duration-200">
                                        <i class="fa fa-plus"></i> Criar Primeiro Tipo de Procedimento
                                    </a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Exibirsão MOBILE -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse ($tipos_procedimento as $tipo_procedimento)
                <div class="p-4 hover:bg-gray-50 transition">
                    <p class="font-semibold text-gray-800 mb-1">{{ $tipo_procedimento->tipo_procedimento_nome }}</p>
                    <p class="text-sm text-gray-600">
                        <strong>Status:</strong> {{ $tipo_procedimento->tipo_procedimento_status ?? '—' }}<br>
                    </p>

                    <!-- Botões -->
                    <div class="flex justify-end mt-2 gap-1">
                        <a href="/tipos_procedimento/{{ $tipo_procedimento->tipo_procedimento_id }}"
                            class="w-8 h-8 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-full p-2 transition">
                            <i class="fas fa-eye text-xs"></i>
                        </a>

                        <a href="{{ route('admin.tipos_procedimento.formulario', $tipo_procedimento->tipo_procedimento_id) }}"
                            class="w-8 h-8 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-full p-2 transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>

                        <button type="button"
                            wire:click="confirmDelete('{{ $tipo_procedimento->tipo_procedimento_id }}')"
                            class="w-8 h-8 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 rounded-full p-2 transition">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">Nenhum tipo_procedimento encontrado.</p>
            @endforelse
        </div>
    </div>

    <!-- Paginação -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-3">
        {{-- Informação de resultados --}}
        @if ($tipos_procedimento->total() > 0)
            <div class="text-sm text-gray-600">
                Mostrando
                <span class="font-semibold text-gray-800">{{ $tipos_procedimento->firstItem() }}</span>
                até
                <span class="font-semibold text-gray-800">{{ $tipos_procedimento->lastItem() }}</span>
                de
                <span class="font-semibold text-gray-800">{{ $tipos_procedimento->total() }}</span>
                registro(s)
                @if ($searchTerm)
                    para "<span class="font-semibold text-blue-600">{{ $searchTerm }}</span>"
                @endif
            </div>
        @endif

        {{-- Links de paginação --}}
        <div>
            {{ $tipos_procedimento->links() }}
        </div>
    </div>

    <!-- Modal de confirmação -->
    @if ($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4"
            style="margin-top: 0!important">
            <div class="bg-white rounded-lg p-4 w-full max-w-md shadow-lg">
                <h2 class="text-lg font-semibold mb-3">Confirmar exclusão</h2>
                <p class="mb-4 text-gray-700">Tem certeza de que deseja excluir este tipo_procedimento?</p>
                <div class="flex justify-end space-x-2 mt-5">
                    <button wire:click="$set('confirmingDelete', false)"
                        class="px-4 py-2 border rounded hover:bg-gray-100 transition text-sm"><i
                            class="fa fa-times fa-fw"></i> Cancelar</button>
                    <button wire:click="delete"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition  text-sm"><i
                            class="fa fa-trash fa-fw"></i> Deletar</button>
                </div>
            </div>
        </div>
    @endif

</div>
