<div class="max-w-7xl mx-auto p-6">
    <h2 class="text-2xl font-bold text-blue-900 mb-6">Relatórios do Sistema</h2>

    <!-- Filtros -->
    <div class="bg-white shadow rounded-lg p-4 mb-6 grid md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm font-semibold text-gray-700">Período inicial</label>
            <input type="date" wire:model="inicio" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Período final</label>
            <input type="date" wire:model="fim" class="w-full border rounded p-2">
        </div>
        <div>
            <label class="text-sm font-semibold text-gray-700">Tipo de Relatório</label>
            <select wire:model="tipo" class="w-full border rounded p-2">
                <option value="">Selecione...</option>
                <option value="geral">Gerencial Geral</option>
                <option value="atendimentos">Atendimentos</option>
                <option value="solicitacoes">Solicitações</option>
                <option value="agentes">Agentes de Saúde</option>
                <option value="pacientes">Pacientes</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button wire:click="gerarRelatorio"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded w-full">
                Gerar
            </button>
            @if($view)
                <button wire:click="exportarPDF"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded w-full">
                    PDF
                </button>
            @endif
        </div>
    </div>

    <!-- Conteúdo -->
    <div wire:loading class="text-gray-600">Carregando relatório...</div>

    @if($view)
        @include($view)
    @endif

    <!-- Gráfico -->
    @if($grafico)
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold mb-4">Evolução no período</h3>
            <livewire:recharts.line-chart :data="$grafico" x-key="data" y-key="total" />
        </div>
    @endif
</div>
