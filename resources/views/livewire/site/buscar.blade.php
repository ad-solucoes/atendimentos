<div class="max-w-4xl mx-auto p-4">
    <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-2">
        <input wire:model.debounce.500ms="query" type="text" placeholder="Buscar por título, conteúdo, número..." class="col-span-2 p-2 border rounded" />
        <div class="flex gap-2">
            <select wire:model="filter_organizacao" class="p-2 border rounded w-full">
                <option value="">Todos os órgãos</option>
                @foreach($organizacoes as $organizacao)
                    <option value="{{ $organizacao->organizacao_id }}">{{ $organizacao->organizacao_nome }}</option>
                @endforeach
            </select>
            <select wire:model="filter_tipo" class="p-2 border rounded w-full">
                <option value="">Todos os tipos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->tipo_id }}">{{ $tipo->tipo_nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-4">
        @if($resultados->count())
            <div class="space-y-4">
                @foreach($resultados as $documento)
                    <article class="p-4 border rounded">
                        <h3 class="text-lg font-semibold"><a href="{{ route('site.documentos.show', $documento->documento_id) }}">{{ $documento->documento_titulo }}</a></h3>
                        <p class="text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($documento->documento_descricao ?? $documento->documento_texto_ocr, 300) }}</p>
                        <div class="text-xs text-gray-500 mt-2">{{ $documento->organicao?->organizacao_nome }} • {{ optional($documento->documento_data_emissao)->format('d/m/Y') }}</div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $resultados->links() }}
            </div>
        @else
            <div class="p-6 text-center text-gray-600">Nenhum documento encontrado.</div>
        @endif
    </div>
</div>
