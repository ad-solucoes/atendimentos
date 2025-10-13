<div class="max-w-md mx-auto mt-0">
    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

    <form wire:submit.prevent="login" class="space-y-4">
        <div>
            <label>Email</label>
            <input type="email" wire:model="email" class="w-full border rounded px-3 py-2">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label>Senha</label>
            <input type="password" wire:model="password" class="w-full border rounded px-3 py-2">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" wire:model="remember" class="mr-2"> <span>Lembrar-me</span>
        </div>

        <div class="flex justify-between items-center mt-3">
            <a href="{{ route('admin.password.request') }}" class="text-blue-600 text-sm">Esqueci minha senha</a>
            <button class="bg-blue-600 text-white px-4 py-2 rounded">Entrar</button>
        </div>
    </form>
</div>
