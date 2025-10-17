<div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm mb-4">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">

        {{-- Data inicial --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Data Inicial</label>
            <input type="date" wire:model.live="filtro_data_inicial"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
        </div>

        {{-- Data final --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Data Final</label>
            <input type="date" wire:model.live="filtro_data_final"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
        </div>

        {{-- Período --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Período</label>
            <select wire:model.live="filtro_periodo"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                <option value="">Todos</option>
                <option value="hoje">Hoje</option>
                <option value="semana">Esta Semana</option>
                <option value="mes">Este Mês</option>
            </select>
        </div>

        {{-- Número da Solicitação --}}
        <div class="flex flex-col">
            <label for="filtro_numero_solicitacao" class="text-sm font-semibold text-gray-700 mb-1">
                Nº da Solicitação:
            </label>
            <input 
                id="filtro_numero_solicitacao"
                type="text"
                wire:model.live.debounce.500ms="filtro_numero_solicitacao"
                placeholder="Ex: S123456"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
            >
        </div>

        {{-- Tipo de Procedimento --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Tipo de Procedimento</label>
            <select wire:model.live="filtro_tipo_procedimento"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                <option value="">Todos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->tipo_procedimento_id }}">{{ $tipo->tipo_procedimento_nome }}</option>
                @endforeach
            </select>
        </div>

        {{-- Procedimento --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Procedimento</label>
            <select wire:model.live="filtro_procedimento"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                <option value="">Todos</option>
                @foreach($procedimentos as $proc)
                    <option value="{{ $proc->procedimento_id }}">{{ $proc->procedimento_nome }}</option>
                @endforeach
            </select>
        </div>

        {{-- Prioridade --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Prioridade</label>
            <select wire:model.live="filtro_prioridade"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                <option value="">Todas</option>
                <option value="normal">Normal</option>
                <option value="urgente">Urgente</option>
                <option value="preferencial">Preferencial</option>
            </select>
        </div>

        {{-- Nº Atendimento --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Nº Atendimento</label>
            <input type="text" wire:model.live="filtro_numero_atendimento"
                   placeholder="Ex: 1234"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
        </div>

        {{-- Nome --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Nome do Paciente</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa fa-search"></i>
                </span>
                <input type="text" wire:model.live="filtro_nome"
                       placeholder="Digite o nome..."
                       class="pl-9 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
            </div>
        </div>

        {{-- CPF --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">CPF</label>
            <input type="text" wire:model.live="filtro_cpf"
                   placeholder="000.000.000-00"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
        </div>

        {{-- SUS --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Cartão SUS</label>
            <input type="text" wire:model.live="filtro_sus"
                   placeholder="Número do CNS"
                   class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
        </div>

        {{-- Status --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Status</label>
            <select wire:model.live="filtro_status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                <option value="pendente">Pendente</option>
                <option value="aguardando">Aguardando</option>
                <option value="agendado">Agendado</option>
                <option value="marcado">Marcado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
                <option value="devolvido">Devolvido</option>
            </select>
        </div>

        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-3">&nbsp;</label>
            <div class="w-full ml-1">
                <button 
                    type="button" 
                    wire:click="
                        $set('filtro_status', '');
                        $set('filtro_data_inicial', '');
                        $set('filtro_data_final', '');
                        $set('filtro_periodo = ''', '');
                        $set('filtro_numero_solicitacao', '');
                        $set('filtro_tipo_procedimento', '');
                        $set('filtro_procedimento', '');
                        $set('filtro_prioridade', '');
                        $set('filtro_numero_atendimento', '');
                        $set('filtro_nome', '');
                        $set('filtro_cpf', '');
                        $set('filtro_sus', '');
                    "
                    class="text-blue-600 text-sm hover:underline ml-0">
                    Limpar filtros
                </button>
            </div>            
        </div>
    </div>
</div>
