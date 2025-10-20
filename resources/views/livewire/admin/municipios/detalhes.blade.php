<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    <div class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <h3 class="text-gray-400 text-sm font-medium">#ID</h3>
                <p class="text-gray-800 font-semibold">{{ formatoId($municipio->municipio_id, 3) }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Nome do Municipio</h3>
                <p class="text-gray-800 font-semibold">{{ $municipio->municipio_nome }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Estado</h3>
                <p class="text-gray-800 font-semibold">{{ $municipio->estado->estado_nome }}</p>
            </div>
            <div>
                <h3 class="text-gray-400 text-sm font-medium">Status</h3>
                @if($municipio->municipio_status)
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">Ativo</span>
                @else
                    <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Inativo</span>
                @endif
            </div>
        </div>

        <!-- Botões -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0">
            <a href="{{ route('admin.municipios.listagem') }}" class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm"><i class="fa fa-times fa-fw"></i> Cancelar</a>
        </div>
    </div>
</div>
