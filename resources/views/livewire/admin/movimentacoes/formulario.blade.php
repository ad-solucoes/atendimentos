<div class="space-y-6">
    {{-- Filtros --}}
    @include('livewire.admin.movimentacoes.partials.filtros')

    @if(count($solicitacoes) > 0)
    {{-- Listagem --}}
    @include('livewire.admin.movimentacoes.partials.listagem')
    @endif
    
    @if(count($selecionadas) > 0)
    {{-- Formulário de movimentação --}}    
    <form wire:submit.prevent="atualizarEmMassa"
        class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm space-y-4">

        <h3 class="text-base font-semibold text-gray-700">Movimentar Selecionadas</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Destino --}}
            <div class="flex flex-col">
                <label class="text-sm font-semibold text-gray-700 mb-1">Destino</label>
                <select wire:model.live="setor_destino_id"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                    <option value="">Selecione...</option>
                    @foreach($destinos as $d)
                        <option value="{{ $d->setor_id }}">{{ $d->setor_nome }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            @if($statusDisponiveis)
                <div class="flex flex-col">
                    <label class="text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select wire:model="status"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                        <option value="">Selecione...</option>
                        @foreach($statusDisponiveis as $st)
                            <option value="{{ $st }}">{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Entregue para + Observação --}}
            @if($mostrarEntrega)
            <div class="flex flex-col md:col-span-2">
                <label class="text-sm font-semibold text-gray-700 mb-1">Entregue Para</label>
                <select wire:model="entregue_para"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full">
                    <option value="">Selecione...</option>
                    <option value="paciente">Paciente</option>
                    <option value="agente_saude">Agente de Saúde</option>
                    <option value="equipe_saude">Equipe de Saúde</option>
                </select>

                <label class="text-sm font-semibold text-gray-700 mt-3 mb-1">Observação</label>
                <textarea wire:model="observacao"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none w-full"
                    rows="2" placeholder="Ex: entregue ao ACS João"></textarea>
            </div>
            @endif
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
