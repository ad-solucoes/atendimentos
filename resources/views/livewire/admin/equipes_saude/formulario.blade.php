<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3 text-center">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Nome da Equipe de Saúde -->
        <div>
            <label class="block font-semibold mb-1">Nome da Equipe de Saúde *</label>
            <input type="text" wire:model="equipe_saude_nome" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('equipe_saude_nome') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Botões -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-2 space-y-2 sm:space-y-0">
            <a href="{{ route('admin.equipes_saude.listagem') }}" class="px-4 py-2 border rounded w-full sm:w-auto text-center">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800">Salvar</button>
        </div>
    </form>
</div>
