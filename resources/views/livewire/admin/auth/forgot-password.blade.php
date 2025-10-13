<div class="max-w-md mx-auto mt-20">
    <h2 class="text-2xl font-bold mb-4 text-center">Recuperar Senha</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="sendResetLink" class="space-y-4">
        <div>
            <label>Email</label>
            <input type="email" wire:model="email" class="w-full border rounded px-3 py-2">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded w-full">Enviar link de redefinição</button>
    </form>
</div>
