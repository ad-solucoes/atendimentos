<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-2">{{ $document->titulo }}</h1>
    <div class="text-sm text-gray-600 mb-4">{{ $document->organizazao?->nome }} • {{ optional($document->data_emissao)->format('d/m/Y') }}</div>

    <div class="mb-4">
        <p>{{ $document->descricao }}</p>
    </div>

    <div class="border p-2">
        @php $primary = $document->arquivos->where('is_primary', true)->first() ?? $document->arquivos->first(); @endphp
        @if($primary)
            @if(Str::contains($primary->tipo,'pdf'))
                <h3>{{ $primary->caminho_armazenamento }}</h3>
                <iframe src="{{ Storage::url($primary->caminho_armazenamento) }}#toolbar=0" class="w-full h-[80vh]"></iframe>
            @else
                <img src="{{ Storage::url($primary->caminho_armazenamento) }}" alt="{{ $document->titulo }}" class="w-full" />
            @endif
            <div class="mt-2">
                <a href="{{ Storage::url($primary->caminho_armazenamento) }}" class="underline">Download do arquivo</a>
            </div>
        @else
            <div class="p-6 text-center text-gray-600">Arquivo não disponível.</div>
        @endif
    </div>
</div>
