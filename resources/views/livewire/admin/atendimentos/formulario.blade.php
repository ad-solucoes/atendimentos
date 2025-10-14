<div class="max-w-5xl mx-auto p-4">
    <form wire:submit.prevent="save" class="space-y-6">
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                <strong>Erros encontrados:</strong>
                <ul class="list-disc ml-5 text-sm mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- DADOS DO ATENDIMENTO --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            {{-- Paciente --}}
            <div class="relative col-span-7">
                <label class="font-semibold text-sm mb-1 block">Paciente <span class="text-red-600">*</span></label>
                <input 
                    type="text"
                    wire:model.live.debounce.400ms="pacienteBusca"
                    wire:keydown.arrow-down.prevent="navegaLista('baixo')"
                    wire:keydown.arrow-up.prevent="navegaLista('cima')"
                    wire:keydown.enter.prevent="selecionarPorEnter"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    placeholder="Digite o nome, CPF ou Cartão do SUS do paciente"
                >

                {{-- Dropdown --}}
                @if(!empty($pacientesEncontrados))
                    {{-- Lista de sugestões --}}
                    <ul class="absolute z-10 bg-white border border-gray-300 w-full mt-1 rounded shadow-md max-h-72 overflow-y-auto">
                        @foreach($pacientesEncontrados as $i => $paciente)
                            <li 
                                wire:click="selecionarPaciente({{ $paciente->paciente_id }})"
                                class="px-3 py-2 cursor-pointer text-sm 
                                    {{ $i === $pacienteSelecionadoIndex ? 'bg-blue-100' : 'hover:bg-gray-100' }}">
                                <div class="font-medium">{{ $paciente->paciente_nome }}</div>
                                <div class="text-sm text-gray-500">
                                    <small>CPF:</small> <span class="font-medium">{{ $paciente->paciente_cpf }}</span> <small>| Cartão do SUS:</small> <span class="font-medium">{{ $paciente->paciente_cns }}</span><br/><small>Nascimento:</small> <span class="font-medium">{{ $paciente->paciente_data_nascimento->format('d/m/Y') }}</span> <small>| Nome da Mãe:</small> <span class="font-medium">{{ $paciente->paciente_nome_mae }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @elseif(strlen($pacienteBusca) > 2 && empty($pacientesEncontrados) && empty($atendimento_paciente_id))
                    <div class="absolute z-10 bg-white border border-gray-300 w-full mt-1 rounded shadow-md text-sm p-3 text-center">
                        Nenhum paciente encontrado.
                        <a href="{{ route('admin.pacientes.formulario') }}" class="text-blue-600 underline block mt-1">
                            + Adicionar novo paciente
                        </a>
                    </div>
                @endif

                @error('atendimento_paciente_id')
                    <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
                @enderror
            </div>

            {{-- Prioridade --}}
            <div class="col-span-5">
                <label class="font-semibold text-sm mb-2 block text-gray-700">Prioridade</label>

                <div class="flex flex-wrap gap-3">
                    @foreach (['Baixa', 'Média', 'Alta'] as $prioridade)
                        <label
                            for="{{ $prioridade }}"
                            class="relative flex items-center gap-2 cursor-pointer select-none px-4 py-2 border rounded-lg shadow-sm text-sm font-medium transition
                                @if($atendimento_prioridade === $prioridade)
                                    @if($prioridade === 'Baixa')
                                    border-green-600 bg-green-50 text-green-700
                                    @elseif($prioridade === 'Média')
                                    border-yellow-600 bg-yellow-50 text-yellow-700
                                    @elseif($prioridade === 'Alta')
                                    border-red-600 bg-red-50 text-red-700
                                    @else
                                    border-blue-600 bg-blue-50 text-blue-700
                                    @endif
                                @else
                                    border-gray-300 text-gray-700 hover:bg-gray-50
                                @endif"
                        >
                            <!-- input real -->
                            <input
                                type="radio"
                                name="atendimento_prioridade"
                                id="{{ $prioridade }}"
                                wire:model.live="atendimento_prioridade"
                                value="{{ $prioridade }}"
                                class="sr-only"
                            >

                            <!-- círculo -->
                            <div
                                class="w-5 h-5 flex items-center justify-center border-2 rounded-full transition
                                    @if($atendimento_prioridade == $prioridade)
                                        @if($prioridade === 'Baixa')
                                        border-green-600 bg-green-600 text-white
                                        @elseif($prioridade === 'Média')
                                        border-yellow-600 bg-yellow-600 text-white
                                        @elseif($prioridade === 'Alta')
                                        border-red-600 bg-red-600 text-white
                                        @else
                                        border-blue-600 bg-blue-600 text-white
                                        @endif
                                        
                                    @else
                                        border-gray-400 bg-white
                                    @endif"
                            >
                                @if($atendimento_prioridade === $prioridade)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </div>

                            <!-- texto -->
                            <span>{{ $prioridade }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Data --}}
            <div>
                <label class="font-semibold text-sm mb-1 block text-gray-700">Data do Atendimento</label>
                <input
                    type="datetime-local"
                    wire:model="atendimento_data"
                    disabled
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-800 cursor-not-allowed
                        border-gray-300 focus:ring-0 focus:outline-none
                        disabled:opacity-100 disabled:text-gray-800 disabled:border-gray-300"
                >
            </div>

            {{-- Status --}}
            <div>
                <label class="font-semibold text-sm mb-1 block">Status</label>
                <select wire:model="atendimento_status" class="w-full border rounded px-3 py-2">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>
        </div>

        {{-- Observação --}}
        <div>
            <label class="font-semibold text-sm mb-1 block">Observações</label>
            <textarea wire:model="atendimento_observacao" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        {{-- SOLICITAÇÕES --}}
        <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-gray-700">Solicitações</h3>
                <button type="button" wire:click="addSolicitacao" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                    <i class="fa fa-plus"></i>
                </button>
            </div>

            @foreach($solicitacoes as $index => $sol)
                <div class="border rounded-lg p-3 mb-3 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tipo de procedimento --}}
                        <div>
                            <label class="text-sm font-semibold">Tipo de Procedimento</label>
                            <select 
                                wire:model="solicitacoes.{{ $index }}.tipo_id" 
                                wire:change="atualizarProcedimentos({{ $index }})"
                                class="w-full border rounded px-2 py-1"
                            >
                                <option value="">Selecione</option>
                                @foreach($tiposProcedimento as $tipo)
                                    <option value="{{ $tipo->tipo_procedimento_id }}">{{ $tipo->tipo_procedimento_nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Procedimento --}}
                        <div>
                            <label class="text-sm font-semibold">Procedimento</label>
                            <select wire:model="solicitacoes.{{ $index }}.procedimento_id" class="w-full border rounded px-2 py-1">
                                <option value="">Selecione</option>
                                @foreach($sol['procedimentos_disponiveis'] ?? [] as $proc)
                                    <option value="{{ $proc->procedimento_id }}">{{ $proc->procedimento_nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Observação --}}
                        <div class="md:col-span-2">
                            {{-- <label class="text-sm font-semibold">Observação</label> --}}
                            <input type="text" wire:model="solicitacoes.{{ $index }}.observacao" class="w-full border rounded px-2 py-1" placeholder="Observação">
                        </div>
                    </div>

                    @if($index > 0)
                    <div class="text-right mt-2">
                        <button type="button" wire:click="removeSolicitacao({{ $index }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- BOTÕES --}}
        <div class="flex justify-center gap-2 pt-4">
            <a href="{{ route('admin.atendimentos.listagem') }}" class="px-4 py-2 border rounded text-sm">
                <i class="fa fa-times"></i> Cancelar
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 text-sm">
                <i class="fa fa-save"></i> Salvar Atendimento
            </button>
        </div>
    </form>
</div>
