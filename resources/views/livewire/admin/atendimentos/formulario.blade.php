<div class="max-w-5xl mx-auto p-4">
    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3 text-center">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold text-sm mb-1 block">Paciente <span class="text-red-600">*</span></label>
                <input type="number" wire:model="atendimento_paciente_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                @error('atendimento_paciente_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="font-semibold text-sm mb-1 block">Prioridade</label>
                <select wire:model="atendimento_prioridade" class="w-full border rounded px-3 py-2">
                    <option>Baixa</option>
                    <option>Média</option>
                    <option>Alta</option>
                </select>
            </div>

            <div>
                <label class="font-semibold text-sm mb-1 block">Data do Atendimento</label>
                <input type="datetime-local" wire:model="atendimento_data" class="w-full border rounded px-3 py-2">
            </div>

            <div>
                <label class="font-semibold text-sm mb-1 block">Status</label>
                <select wire:model="atendimento_status" class="w-full border rounded px-3 py-2">
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>
        </div>

        <div>
            <label class="font-semibold text-sm mb-1 block">Observações</label>
            <textarea wire:model="atendimento_observacao" rows="2" class="w-full border rounded px-3 py-2"></textarea>
        </div>

        <div class="border-t pt-4">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-gray-700">Solicitações</h3>
                <button type="button" wire:click="addSolicitacao" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                    <i class="fa fa-plus"></i> Adicionar
                </button>
            </div>

            @foreach($solicitacoes as $index => $sol)
                <div class="border rounded-lg p-3 mb-3 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold">Procedimento <span class="text-red-600">*</span></label>
                            <select wire:model="solicitacoes.{{ $index }}.procedimento_id" class="w-full border rounded px-2 py-1">
                                <option value="">Selecione</option>
                                @foreach($procedimentos as $proc)
                                    <option value="{{ $proc->procedimento_id }}">{{ $proc->procedimento_nome }}</option>
                                @endforeach
                            </select>
                            @error("solicitacoes.$index.procedimento_id") 
                                <span class="text-red-600 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Observação</label>
                            <input type="text" wire:model="solicitacoes.{{ $index }}.observacao" class="w-full border rounded px-2 py-1">
                        </div>
                    </div>

                    <div class="text-right mt-2">
                        <button type="button" wire:click="removeSolicitacao({{ $index }})" class="text-red-600 text-sm hover:underline">
                            <i class="fa fa-trash"></i> Remover
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center gap-2 pt-4">
            <a href="{{ route('admin.atendimentos.listagem') }}" class="px-4 py-2 border rounded text-sm"><i class="fa fa-times"></i> Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 text-sm">
                <i class="fa fa-save"></i> Salvar Atendimento
            </button>
        </div>
    </form>
</div>
