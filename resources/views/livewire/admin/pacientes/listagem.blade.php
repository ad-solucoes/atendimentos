<div class="space-y-6">
    <!-- Filtros e Ações -->
    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            {{-- Nome do Paciente --}}
            <div class="flex flex-col">
                <label for="filtroNome" class="text-sm font-semibold text-gray-700 mb-1">
                    Nome do Paciente:
                </label>
                <input 
                    id="filtroNome"
                    type="text"
                    wire:model.live.debounce.500ms="filtroNome"
                    placeholder="Digite o nome..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
            </div>

            {{-- CPF --}}
            <div class="flex flex-col">
                <label for="filtroCpf" class="text-sm font-semibold text-gray-700 mb-1">
                    CPF:
                </label>
                <input 
                    id="filtroCpf"
                    type="text"
                    wire:model.live.debounce.500ms="filtroCpf"
                    placeholder="999.999.999-99"
                    maxlength="14"
                    x-data 
                    x-mask="999.999.999-99"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
            </div>

            {{-- Cartão SUS --}}
            <div class="flex flex-col">
                <label for="filtroSus" class="text-sm font-semibold text-gray-700 mb-1">
                    Cartão SUS:
                </label>
                <input 
                    id="filtroSus"
                    type="text"
                    wire:model.live.debounce.500ms="filtroSus"
                    placeholder="999 9999 9999 9999"
                    maxlength="18"
                    x-data 
                    x-mask="999 9999 9999 9999"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
            </div>

            {{-- Nome da Mãe --}}
            <div class="flex flex-col">
                <label for="filtroMae" class="text-sm font-semibold text-gray-700 mb-1">
                    Nome da Mãe:
                </label>
                <input 
                    id="filtroMae"
                    type="text"
                    wire:model.live.debounce.500ms="filtroMae"
                    placeholder="Digite o nome da mãe..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                >
            </div>

            {{-- Ações --}}
            <div class="flex flex-col justify-end items-end">
                <a href="{{ route('admin.pacientes.formulario') }}"
                class="inline-flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg shadow transition-all duration-200 text-sm">
                    <i class="fa fa-plus"></i> Novo Paciente
                </a>
            </div>
        </div>

        {{-- Linha inferior com paginação e total --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-4 gap-3">
            <div class="flex items-center gap-2">
                <label class="text-sm font-semibold text-gray-700">Registros por página:</label>
                <select wire:model.live="perPage"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-24">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <button type="button" wire:click="$set('filtroNome', ''); $set('filtroCpf', ''); $set('filtroSus', ''); $set('filtroMae', '')" class="text-blue-600 text-sm hover:underline ml-3">
                    Limpar filtros
                </button>
            </div>

            <small class="text-gray-600 text-sm">
                <i class="fa fa-info-circle text-gray-400"></i>
                Total: <span class="font-semibold text-gray-800">{{ $pacientes->total() }}</span> registro(s)
            </small>
        </div>        
    </div>

    <!-- Tabela principal -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <!-- Cabeçalho (desktop) -->
        <table class="hidden md:table min-w-full">
            <thead>
                <tr class="bg-gray-100 text-left text-sm text-gray-600">
                    <th class="px-2 py-2 text-center font-semibold" width="100" wire:click="sortByField('paciente_status')" style="cursor: pointer;" title="Clique para ordenar">
                        Status
                        @include('livewire.partials._sort-icon', ['field' => 'paciente_status'])
                    </th>
                    <th class="px-2 py-2 font-semibold" wire:click="sortByField('paciente_nome')" style="cursor: pointer;" title="Clique para ordenar">
                        Nome do Paciente
                        @include('livewire.partials._sort-icon', ['field' => 'paciente_nome'])
                    </th>
                    <th class="px-2 py-2 font-semibold">Cartão do SUS</th>
                    <th class="px-2 py-2 font-semibold">CPF nº</th>
                    <th class="px-2 py-2 font-semibold">Nome da Mãe</th>
                    <th class="px-2 py-2 text-center font-semibold" width="140">Ações</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $temFiltros = $filtroNome || $filtroCpf || $filtroSus || $filtroMae;
                @endphp

                <tr wire:loading.class.remove="hidden" wire:target="filtroNome, filtroCpf, filtroSus, filtroMae" class="hidden">
                    <td colspan="6" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center space-y-1.5 text-gray-600">
                            {{-- Spinner visível e animado --}}
                            <div class="relative flex items-center justify-center">
                                <div class="w-10 h-10 border-4 border-gray-300 rounded-full"></div>
                                <div class="absolute w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                            </div>
                            {{-- Mensagem --}}
                            <div>
                                <p class="font-semibold text-gray-700">Carregando registros...</p>
                                <p class="text-sm text-gray-500">Aguarde um momento</p>
                            </div>

                        </div>
                    </td>
                </tr>
                @forelse ($pacientes as $paciente)
                    <tr class="border-t hover:bg-gray-50 transition" wire:loading.remove wire:target="filtroNome, filtroCpf, filtroSus, filtroMae">
                        <td class="px-2 py-1 text-sm font-medium text-center">
                            @if($paciente->paciente_status)
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Ativo</span>
                            @else
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inativo</span>
                            @endif
                        </td>
                        <td class="px-2 py-1 text-sm font-medium text-gray-800">{{ $paciente->paciente_nome }}</td>
                        <td class="px-2 py-1 text-sm text-gray-800">{{ $paciente->paciente_cns }}</td>
                        <td class="px-2 py-1 text-sm text-gray-800">{{ $paciente->paciente_cpf }}</td>
                        <td class="px-2 py-1 text-sm text-gray-800">{{ $paciente->paciente_nome_mae }}</td>
                        <td class="px-2 py-1 text-center space-x-0">
                            <!-- Ver -->
                            <a href="{{ route('admin.pacientes.detalhes', $paciente->paciente_id) }}"
                               class="w-7 h-6 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 p-2 transition"
                               title="Ver Paciente">
                                <i class="fas fa-eye text-xs"></i>
                            </a>

                            <!-- Editar -->
                            <a href="{{ route('admin.pacientes.formulario', $paciente->paciente_id) }}"
                               class="w-7 h-6 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 p-2 transition"
                               title="Editar Paciente">
                                <i class="fas fa-pen text-xs"></i>
                            </a>

                            <!-- Excluir -->
                            <button type="button"
                                    wire:click="confirmDelete('{{ $paciente->paciente_id }}')"
                                    class="w-7 h-6 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 p-2 transition"
                                    title="Excluir Paciente">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr wire:loading.remove wire:target="filtroNome, filtroCpf, filtroSus, filtroMae">
                        <td colspan=6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-1.5">

                                <div class="text-gray-400">
                                    <i class="fa fa-exclamation-triangle text-4xl"></i>
                                </div>

                                <div>
                                    @if($temFiltros)
                                        <h4 class="text-gray-700 font-semibold text-lg">
                                            Nenhum paciente encontrado
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Tente buscar por outros termos ou
                                            <button 
                                                wire:click="
                                                        $set('filtroNome', '');
                                                        $set('filtroCpf', '');
                                                        $set('filtroSus', '');
                                                        $set('filtroMae', '');
                                                    "
                                                    class="text-blue-600 hover:underline font-medium">
                                                limpe o filtro
                                            </button>.
                                        </p>
                                    @else
                                        <h4 class="text-gray-700 font-semibold text-lg">
                                            Nenhum paciente cadastrado
                                        </h4>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Comece criando um novo paciente.
                                        </p>
                                    @endif
                                </div>

                                @if(!$temFiltros)
                                    <a href="{{ route('admin.pacientes.formulario') }}" class="mt-3 inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2.5 rounded-lg shadow transition-all duration-200">
                                        <i class="fa fa-plus"></i> Criar Primeiro Paciente
                                    </a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Versão MOBILE -->
        <div class="block md:hidden divide-y divide-gray-100">
            @forelse ($pacientes as $paciente)
                <div class="p-4 hover:bg-gray-50 transition">
                    <p class="font-semibold text-gray-800 mb-1">{{ $paciente->paciente_nome }}</p>
                    <p class="text-sm text-gray-600">
                        <strong>Status:</strong> {{ $paciente->paciente_status ?? '—' }}<br>
                    </p>

                    <!-- Botões -->
                    <div class="flex justify-end mt-2 gap-1">
                        <a href="/pacientes/{{ $paciente->paciente_id }}" 
                           class="w-8 h-8 inline-flex items-center justify-center bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-full p-2 transition">
                            <i class="fas fa-eye text-xs"></i>
                        </a>

                        <a href="{{ route('admin.pacientes.formulario', $paciente->paciente_id) }}"
                           class="w-8 h-8 inline-flex items-center justify-center bg-yellow-100 text-yellow-700 hover:bg-yellow-200 rounded-full p-2 transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>

                        <button type="button" 
                                wire:click="confirmDelete('{{ $paciente->paciente_id }}')" 
                                class="w-8 h-8 inline-flex items-center justify-center bg-red-100 text-red-700 hover:bg-red-200 rounded-full p-2 transition">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">Nenhum paciente encontrado.</p>
            @endforelse
        </div>
    </div>

    <!-- Paginação -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-3">
        {{-- Informação de resultados --}}
        @if ($pacientes->total() > 0)
            <div class="text-sm text-gray-600">
                Mostrando
                <span class="font-semibold text-gray-800">{{ $pacientes->firstItem() }}</span>
                até
                <span class="font-semibold text-gray-800">{{ $pacientes->lastItem() }}</span>
                de
                <span class="font-semibold text-gray-800">{{ $pacientes->total() }}</span>
                registro(s)
            </div>
        @endif

         {{-- Links de paginação --}}
        <div>
            {{ $pacientes->links() }}
        </div>
    </div>

    <!-- Modal de confirmação -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4" style="margin-top: 0!important">
            <div class="bg-white rounded-lg p-4 w-full max-w-md shadow-lg">
                <h2 class="text-lg font-semibold mb-3">Confirmar exclusão</h2>
                <p class="mb-4 text-gray-700">Tem certeza de que deseja excluir este paciente?</p>
                <div class="flex justify-end space-x-2 mt-5">
                    <button wire:click="$set('confirmingDelete', false)" class="px-4 py-2 border rounded hover:bg-gray-100 transition text-sm"><i class="fa fa-times fa-fw"></i> Cancelar</button>
                    <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition  text-sm"><i class="fa fa-trash fa-fw"></i> Excluir</button>
                </div>
            </div>
        </div>
    @endif

</div>
