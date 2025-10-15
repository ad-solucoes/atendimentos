<div class="max-w-md mx-auto mt-0">
    <h2 class="text-2xl font-bold mb-6 text-center text-blue-900">Acesso ao Sistema</h2>

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fa-solid fa-envelope mr-1 text-blue-600"></i> Email
            </label>
            <input type="email" wire:model="email"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fa-solid fa-lock mr-1 text-blue-600"></i> Senha
            </label>
            <input type="password" wire:model="password"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center text-sm text-gray-700">
                <input type="checkbox" wire:model="remember" class="mr-2 accent-blue-600">
                <span>Lembrar-me</span>
            </label>

            <a href="{{ route('admin.password.request') }}"
                class="text-blue-700 text-sm hover:underline">Esqueci minha senha</a>
        </div>

        <button type="submit"
            class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-lg mt-4 transition duration-200 flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i>
            Entrar
        </button>
    </form>

    <div class="text-center text-xs text-gray-500 mt-6">
        <p>Dica: utilize suas credenciais do painel administrativo.</p>
    </div>
</div>
