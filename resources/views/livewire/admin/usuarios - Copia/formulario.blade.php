<div class="max-w-2xl mx-auto">
    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-3">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block font-semibold">Nome *</label>
            <input type="text" wire:model="name" class="border rounded px-3 py-2 w-full">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold">E-mail *</label>
            <input type="text" wire:model="email" class="border rounded px-3 py-2 w-full">
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold">Admin</label>
            <select wire:model.live="is_admin" class="border rounded px-3 py-2 w-full">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
            @error('is_admin') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        @if(!$is_admin)
        <div>
            <label class="block font-semibold">Setor</label>
            <select wire:model="setor_id" class="border rounded px-3 py-2 w-full">
                <option value="">-- Selecionar --</option>
                @foreach($setores as $setor)
                    <option value="{{ $setor->setor_id }}">{{ $setor->setor_nome }}</option>
                @endforeach
            </select>
            @error('setor_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif

        <div class="pt-4 flex justify-center space-x-1">
            <a href="{{ route('admin.usuarios.listagem') }}" class="px-4 py-2 border rounded">Cancelar</a>
            <button type="submit" class="px-4 py-2 bg-blue-700 text-white rounded">Salvar</button>
        </div>
    </form>
</div>
