<div class="space-y-6">
    {{-- 🎯 FILTROS --}}
    <div class="bg-white shadow-sm rounded-xl p-4 border border-gray-200 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">

            <div>
                <label class="text-sm font-medium text-gray-700">Data Inicial</label>
                <input type="date" wire:model.live="filtro_data_inicial"
                    class="w-full border-gray-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Data Final</label>
                <input type="date" wire:model.live="filtro_data_final"
                    class="w-full border-gray-300 rounded-lg text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Período</label>
                <select wire:model.live="filtro_periodo"
                    class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Todos</option>
                    <option value="hoje">Hoje</option>
                    <option value="semana">Esta Semana</option>
                    <option value="mes">Este Mês</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Tipo de Procedimento</label>
                <select wire:model.live="filtro_tipo_procedimento"
                    class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Todos</option>
                    @foreach($tipos as $tipo)
                        <option value="{{ $tipo->tipo_procedimento_id }}">{{ $tipo->tipo_procedimento_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Procedimento</label>
                <select wire:model.live="filtro_procedimento"
                    class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Todos</option>
                    @foreach($procedimentos as $proc)
                        <option value="{{ $proc->procedimento_id }}">{{ $proc->procedimento_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Prioridade</label>
                <select wire:model.live="filtro_prioridade"
                    class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Todas</option>
                    <option value="normal">Normal</option>
                    <option value="urgente">Urgente</option>
                    <option value="preferencial">Preferencial</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Nº Atendimento</label>
                <input type="text" wire:model.live="filtro_numero_atendimento"
                    class="w-full border-gray-300 rounded-lg text-sm" placeholder="Ex: 1234">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Nome do Paciente</label>
                <input type="text" wire:model.live="filtro_nome"
                    class="w-full border-gray-300 rounded-lg text-sm" placeholder="Digite o nome...">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">CPF</label>
                <input type="text" wire:model.live="filtro_cpf"
                    class="w-full border-gray-300 rounded-lg text-sm" placeholder="000.000.000-00">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Cartão SUS</label>
                <input type="text" wire:model.live="filtro_sus"
                    class="w-full border-gray-300 rounded-lg text-sm" placeholder="Número do CNS">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Setor</label>
                <select wire:model.live="filtro_setor"
                    class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Todos</option>
                    @foreach($setores as $s)
                        <option value="{{ $s->setor_id }}">{{ $s->setor_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">Status</label>
                <select wire:model.live="filtro_status"
                    class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Todos</option>
                    <option value="aguardando">Aguardando</option>
                    <option value="agendado">Agendado</option>
                    <option value="marcado">Marcado</option>
                    <option value="entregue">Entregue</option>
                    <option value="cancelado">Cancelado</option>
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
                            <input type="checkbox" wire:model="selecionadas" value="{{ $s->solicitacao_id }}">
                        </td>
                        <td class="p-2">{{ $s->solicitacao_id }}</td>
                        <td class="p-2">{{ $s->atendimento->paciente->paciente_nome ?? '-' }}</td>
                        <td class="p-2">{{ $s->procedimento->procedimento_nome ?? '-' }}</td>
                        <td class="p-2">{{ $s->localizacaoAtual->setor_nome ?? '-' }}</td>
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

        <div class="p-3">
            {{ $solicitacoes->links() }}
        </div>
    </div>

    {{-- 🚚 AÇÕES EM MASSA --}}
    <form wire:submit.prevent="atualizarEmMassa"
        class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 space-y-4">

        <h3 class="text-base font-medium text-gray-700">Movimentar Selecionadas</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-sm font-medium">Novo Status</label>
                <select wire:model="status" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Selecione...</option>
                    <option value="aguardando">Aguardando</option>
                    <option value="agendado">Agendado</option>
                    <option value="marcado">Marcado</option>
                    <option value="entregue">Entregue</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Destino</label>
                <select wire:model="filtro_setor" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Selecione...</option>
                    @foreach($setores as $s)
                        <option value="{{ $s->setor_id }}">{{ $s->setor_nome }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Entregue para</label>
                <select wire:model="entregue_para" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">Nenhum</option>
                    <option value="paciente">Paciente</option>
                    <option value="agente_saude">Agente de Saúde</option>
                    <option value="equipe_saude">Equipe de Saúde</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Observação</label>
                <input type="text" wire:model="observacao"
                    class="w-full border-gray-300 rounded-lg text-sm" placeholder="Ex: entregue ao ACS João">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-700 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fa-solid fa-arrow-right mr-1"></i> Movimentar Selecionadas
            </button>
        </div>
    </form>
</div>
