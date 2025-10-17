<div class="max-w-5xl mx-auto px-0 sm:px-6 lg:px-8 py-4 space-y-8">
    {{-- 🧾 Formulário de Movimentação --}}
    <form wire:submit.prevent="salvar" class="space-y-4">

        {{-- Novo Status --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">
                Novo Status <span class="text-red-600">*</span>
            </label>
            <select wire:model="status"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Selecionar --</option>
                <option value="aguardando">Aguardando</option>
                <option value="agendado">Agendado</option>
                <option value="marcado">Marcado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
                <option value="devolvido">Devolvido</option>
            </select>
            @error('status')
                <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
            @enderror
        </div>

        {{-- Destino --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">
                Destino (Setor)
            </label>
            <select wire:model="setor_destino_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">Automático</option>
                @foreach($setores as $s)
                    <option value="{{ $s->setor_id }}">{{ $s->setor_nome }}</option>
                @endforeach
            </select>
            <small class="text-gray-400 text-xs mt-1">Se deixar em branco, seguirá o fluxo automático.</small>
        </div>

        {{-- Entregue para --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">
                Entregue Para
            </label>
            <select wire:model="entregue_para"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Nenhum --</option>
                <option value="paciente">Paciente</option>
                <option value="agente_saude">Agente de Saúde</option>
                <option value="equipe_saude">Equipe de Saúde</option>
            </select>
        </div>

        {{-- Observação --}}
        <div class="flex flex-col">
            <label class="text-sm font-semibold text-gray-700 mb-1">Observação</label>
            <textarea wire:model="observacao"
                        rows="3"
                        placeholder="Descreva observações sobre esta movimentação..."
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
        </div>

        {{-- Botões --}}
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-2 space-y-2 sm:space-y-0">
            <a href="{{ route('admin.solicitacoes.detalhes', $solicitacao_id) }}"
                class="px-4 py-2 border border-gray-300 rounded-lg w-full sm:w-auto text-center text-sm hover:bg-gray-50">
                <i class="fa fa-times fa-fw"></i> Cancelar
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-700 text-white rounded-lg w-full sm:w-auto hover:bg-blue-800 text-sm">
                <i class="fa fa-save fa-fw"></i> Salvar Movimentação
            </button>
        </div>
    </form>

    {{-- 📜 Histórico de Movimentações --}}
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fa fa-clock-rotate-left text-blue-600"></i> Histórico de Movimentações
    </h3>

    @if($movimentacoes->isEmpty())
        <p class="text-gray-500 text-sm">Nenhuma movimentação registrada para esta solicitação.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-3 py-2 text-left font-medium">Data</th>
                        <th class="px-3 py-2 text-left font-medium">Usuário</th>
                        <th class="px-3 py-2 text-left font-medium">Tipo</th>
                        <th class="px-3 py-2 text-left font-medium">Destino</th>
                        <th class="px-3 py-2 text-left font-medium">Entregue Para</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movimentacoes as $mov)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($mov->movimentacao_data)->format('d/m/Y H:i') }}</td>
                            <td class="px-3 py-2">{{ $mov->usuario->name ?? '-' }}</td>
                            <td class="px-3 py-2 capitalize">{{ $mov->movimentacao_tipo }}</td>
                            <td class="px-3 py-2">{{ $mov->setor->setor_nome ?? '-' }}</td>
                            <td class="px-3 py-2 capitalize">{{ $mov->movimentacao_entregue_para ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
