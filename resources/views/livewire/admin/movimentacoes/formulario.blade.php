<div class="space-y-6">

    {{-- 🎯 FILTROS --}}
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
                    <option value="">Todos</option>
                    <option value="pendente">Pendente</option>
                    <option value="aguardando">Aguardando</option>
                    <option value="agendado">Agendado</option>
                    <option value="marcado">Marcado</option>
                    <option value="entregue">Entregue</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="devolvido">Devolvido</option>
                </select>
            </div>
        </div>
    </div>

    {{-- 📋 LISTAGEM --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-700">
                <tr>
                    <th class="p-2 text-center"><input type="checkbox" wire:model.live="selecionadas_all"></th>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Paciente</th>
                    <th class="p-2 text-left">Procedimento</th>
                    <th class="p-2 text-left">Setor Atual</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitacoes as $s)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2 text-center">
                            <input type="checkbox" wire:model.live="selecionadas" value="{{ $s->solicitacao_id }}">
                        </td>
                        <td class="p-2">{{ $s->solicitacao_id }}</td>
                        <td class="p-2">{{ $s->atendimento->paciente->paciente_nome ?? '-' }}</td>
                        <td class="p-2">{{ $s->procedimento->procedimento_nome ?? '-' }}</td>
                        <td class="p-2">{{ $s->localizacao_atual->setor_nome ?? '-' }}</td>
                        <td class="p-2 capitalize">{{ $s->solicitacao_status }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($s->solicitacao_data)->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 p-4">Nenhuma solicitação encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $solicitacoes->links() }}</div>
    </div>

    {{-- 🚚 MOVIMENTAÇÃO --}}
    @if(count($selecionadas) > 0)
    <form wire:submit.prevent="atualizarEmMassa"
        class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm space-y-4">

        <h3 class="text-base font-semibold text-gray-700">Movimentar Selecionadas</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Status --}}
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-1">Novo Status</label>
                <select wire:model="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                    <option value="">Selecione...</option>
                    <option value="aguardando">Aguardando</option>
                    <option value="agendado">Agendado</option>
                    <option value="marcado">Marcado</option>
                    <option value="entregue">Entregue</option>
                    <option value="cancelado">Cancelado</option>
                    <option value="devolvido">Devolvido</option>
                </select>
            </div>

            {{-- Destino --}}
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-1">Destino</label>
                <select wire:model="setor_destino_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                    @foreach($setores as $s)
                        <option value="{{ $s->setor_id }}">{{ $s->setor_nome }}</option>
                    @endforeach
                </select>
                <small class="text-gray-400 text-xs mt-1">Deixa vazio para seguir fluxo automático.</small>
            </div>

            {{-- Entregue para --}}
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-1">Entregue para</label>
                <select wire:model="entregue_para"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                    <option value="">Nenhum</option>
                    <option value="paciente">Paciente</option>
                    <option value="agente_saude">Agente de Saúde</option>
                    <option value="equipe_saude">Equipe de Saúde</option>
                </select>
            </div>

            {{-- Observação --}}
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-1">Observação</label>
                <input type="text" wire:model="observacao"
                    placeholder="Ex: entregue ao ACS João"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg shadow transition-all duration-200 text-sm">
                <i class="fa-solid fa-arrow-right"></i> Movimentar Selecionadas
            </button>
        </div>
    </form>
    @endif
</div>
