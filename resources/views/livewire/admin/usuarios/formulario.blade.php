<div class="max-w-4xl mx-auto px-0 sm:px-6 lg:px-8 py-">
    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3 text-center">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <!-- Nome do Usuário -->
        <div>
            <label class="block text-sm font-semibold mb-1">Nome do Usuário <span class="text-red-600 text-sm">*</span></label>
            <input type="text" wire:model="name" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
            @error('name') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-semibold mb-1">Status <span class="text-red-600 text-sm">*</span></label>
            <select wire:model="status" class="border rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="1">Ativo</option>
                <option value="0">Inativo</option>
            </select>
            @error('status') <span class="text-red-600 text-sm font-semibold">{{ $message }}</span> @enderror
        </div>

        <!-- Botões -->
        <div class="pt-4 flex flex-col sm:flex-row justify-center sm:space-x-1 space-y-1 sm:space-y-0">
            <a href="{{ route('admin.usuarios.listagem') }}" class="px-4 py-2 border rounded w-full sm:w-auto text-center text-sm"><i class="fa fa-times fa-fw"></i> Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded w-full sm:w-auto hover:bg-blue-800 text-sm"><i class="fa fa-save fa-fw"></i> Salvar Registro</button>
        </div>
    </form>
</div>
