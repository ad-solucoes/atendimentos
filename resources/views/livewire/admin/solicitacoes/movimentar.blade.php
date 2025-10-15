<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    <form wire:submit.prevent="salvar" class="space-y-4">
        <!-- Novo Status -->
        <div>
            <label class="block text-sm font-semibold mb-1">
                Novo Status <span class="text-red-600 text-sm">*</span>
            </label>
            <select wire:model="status"
                    class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Selecionar --</option>
                <option value="aguardando">Aguardando</option>
                <option value="agendado">Agendado</option>
                <option value="marcado">Marcado</option>
                <option value="entregue">Entregue</option>
                <option value="cancelado">Cancelado</option>
            </select>
            @error('status') 
                <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Entregue para -->
        <div>
            <label class="block text-sm font-semibold mb-1">
                Entregue Para <span class="text-red-600 text-sm">*</span>
            </label>
            <select wire:model="entregue_para"
                    class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">-- Selecionar --</option>
                <option value="paciente">Paciente</option>
                <option value="agente_saude">Agente de Saúde</option>
                <option value="equipe_saude">Equipe de Saúde</option>
            </select>
            @error('entregue_para') 
                <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Observação -->
        <div>
            <label class="block text-sm font-semibold mb-1">Observação</label>
            <textarea wire:model="observacao"
                      class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      rows="3"
                      placeholder="Descreva observações sobre esta movimentação..."></textarea>
            @error('observacao') 
                <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> 
            @enderror
        </div>

        <!-- Botões -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0">
            <a href="{{ route('admin.solicitacoes.detalhes', $solicitacao_id) }}"
               class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm">
                <i class="fa fa-times fa-fw"></i> Cancelar
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800 text-sm">
                <i class="fa fa-save fa-fw"></i> Salvar Movimentação
            </button>
        </div>
    </form>
</div>
