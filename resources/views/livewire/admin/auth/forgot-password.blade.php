<div class="w-full">
    <h2 class="text-2xl font-bold text-center text-blue-900 mb-2">Recuperar Senha</h2>
    <p class="text-center text-gray-600 mb-6 text-sm">
        Informe o email associado à sua conta para receber o link de redefinição.
    </p>

    @if (session('message'))
        <div
            class="flex items-center justify-center bg-green-50 text-green-800 px-4 py-3 rounded-lg mb-4 border border-green-200">
            <i class="fa fa-check-circle mr-2"></i>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="flex items-center justify-center bg-red-50 text-red-800 px-4 py-3 rounded-lg mb-4 border border-red-200">
            <i class="fa fa-times-circle mr-2"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="sendResetLink" class="space-y-5" autocomplete="off">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <i class="fa fa-envelope"></i>
                </span>
                <input type="email" wire:model="email" id="email" placeholder="Digite seu email cadastrado"
                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>
            @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 rounded-lg transition">
            <i class="fa fa-paper-plane"></i> Enviar Link de Redefinição
        </button>

        <div class="text-center mt-3">
            <a href="{{ route('admin.login') }}" class="inline-flex items-center text-sm text-blue-700 hover:underline">
                <i class="fa fa-arrow-left mr-1"></i> Voltar ao Login
            </a>
        </div>
    </form>

    <div class="mt-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg text-sm">
        <div class="flex items-center mb-2">
            <i class="fa fa-info-circle mr-2"></i>
            <h5 class="font-semibold">Dica</h5>
        </div>
        <ul class="list-disc list-inside text-xs text-blue-700 space-y-1">
            <li>O link expira em até 60 minutos</li>
            <li>Exibirifique também sua caixa de spam</li>
            <li>Se não receber, solicite novamente após alguns minutos</li>
        </ul>
    </div>
</div>
