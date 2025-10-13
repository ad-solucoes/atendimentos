<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 space-y-6">
    <h2 class="text-xl font-bold text-gray-700">Movimentar Solicitação</h2>

    @if (session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="salvar" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Novo Status</label>
            <select wire:model="status" class="w-full mt-1 border-gray-300 rounded-lg">
                <option value="">Selecione...</option>
                <option value="aguardando">Aguardando</option>
                <option value="agendado">Agendado</option>
                <option value="marcado">Marcado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Entregue para</label>
            <select wire:model="entregue_para" class="w-full mt-1 border-gray-300 rounded-lg">
                <option value="">Nenhum</option>
                <option value="paciente">Paciente</option>
                <option value="agente_saude">Agente de Saúde</option>
                <option value="equipe_saude">Equipe de Saúde</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Observação</label>
            <textarea wire:model="observacao" class="w-full mt-1 border-gray-300 rounded-lg" rows="3"></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold">
            Salvar Movimentação
        </button>
    </form>
</div>
