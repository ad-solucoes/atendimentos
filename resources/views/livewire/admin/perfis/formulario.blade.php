<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Nome do Perfil -->
        <div>
            <label class="block text-sm font-semibold mb-1">Nome do Perfil <span
                    class="text-red-600 text-sm">*</span></label>
            <input type="text" wire:model="name"
                class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('name')
                <span class="text-red-600 text-sm font-semibold">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botões -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0">
            <a href="{{ route('admin.perfis.listagem') }}"
                class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm"><i class="fa fa-times fa-fw"></i>
                Cancelar</a>
            <button type="submit"
                class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800 text-sm"><i
                    class="fa fa-save fa-fw"></i> Salvar Registro</button>
        </div>
    </form>
</div>
