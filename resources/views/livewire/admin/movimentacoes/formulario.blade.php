<div class="space-y-6">
    <h2 class="text-xl font-bold text-gray-700">Movimentações em Massa</h2>

    @if (session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded">{{ session('message') }}</div>
    @endif

    <div class="flex gap-3 items-end">
        <div>
            <label class="text-sm font-medium">Filtrar por Status</label>
            <select wire:model.live="filtro_status" class="border-gray-300 rounded-lg">
                <option value="">Todos</option>
                <option value="aguardando">Aguardando</option>
                <option value="agendado">Agendado</option>
                <option value="marcado">Marcado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
    </div>

    <form wire:submit.prevent="atualizarEmMassa" class="bg-white shadow rounded-xl p-4 space-y-4">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-2"><input type="checkbox" wire:model.live="selecionadas_all"></th>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Número</th>
                    <th class="p-2 text-left">Status Atual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitacoes as $s)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-2">
                            <input type="checkbox" wire:model="selecionadas" value="{{ $s->solicitacao_id }}">
                        </td>
                        <td class="p-2">{{ $s->solicitacao_id }}</td>
                        <td class="p-2">{{ $s->solicitacao_numero }}</td>
                        <td class="p-2">{{ ucfirst($s->solicitacao_status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4">
            <div>
                <label class="text-sm font-medium">Novo Status</label>
                <select wire:model="status" class="w-full border-gray-300 rounded-lg">
                    <option value="">Selecione...</option>
                    <option value="aguardando">Aguardando</option>
                    <option value="agendado">Agendado</option>
                    <option value="marcado">Marcado</option>
                    <option value="entregue">Entregue</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Entregue para</label>
                <select wire:model="entregue_para" class="w-full border-gray-300 rounded-lg">
                    <option value="">Nenhum</option>
                    <option value="paciente">Paciente</option>
                    <option value="agente_saude">Agente de Saúde</option>
                    <option value="equipe_saude">Equipe de Saúde</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Observação</label>
                <input wire:model="observacao" type="text" class="w-full border-gray-300 rounded-lg">
            </div>
        </div>

        <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            Movimentar Selecionadas
        </button>
    </form>
</div>
