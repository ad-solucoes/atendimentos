<div class="w-full">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-blue-900">
            {{ $mustChange ? 'Primeira Senha' : 'Alterar Senha' }}
        </h2>
        <p class="text-gray-600 text-sm mt-2">
            {{ $mustChange
                ? 'Por segurança, altere sua senha temporária para continuar.'
                : 'Mantenha sua conta segura atualizando sua senha regularmente.'
            }}
        </p>
    </div>

    @if($mustChange)
        <div class="flex items-start gap-3 bg-yellow-50 border border-yellow-300 text-yellow-800 p-4 rounded-lg mb-6">
            <i class="fa fa-exclamation-triangle text-yellow-600 text-xl mt-1"></i>
            <div>
                <h5 class="font-semibold text-sm mb-1">Alteração Obrigatória</h5>
                <p class="text-xs">Por segurança, você deve alterar sua senha temporária antes de acessar o sistema.</p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="updatePassword" class="space-y-5" autocomplete="off">
        @csrf

        <!-- Senha atual -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fa fa-lock text-blue-600 mr-1"></i> Senha Atual
            </label>
            <input type="password" wire:model="current_password" placeholder="Digite sua senha atual"
                class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <p class="text-gray-500 text-xs mt-1">Confirme sua identidade</p>
            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Nova senha -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fa fa-key text-blue-600 mr-1"></i> Nova Senha
            </label>
            <input type="password" wire:model="password" placeholder="Digite sua nova senha"
                class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <p class="text-gray-500 text-xs mt-1">Mínimo 8 caracteres com letras, números e símbolos</p>
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Confirmar nova senha -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                <i class="fa fa-check-circle text-blue-600 mr-1"></i> Confirmar Nova Senha
            </label>
            <input type="password" wire:model="password_confirmation" placeholder="Confirme sua nova senha"
                class="w-full border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <p class="text-gray-500 text-xs mt-1">Digite a mesma senha novamente</p>
            @error('password_confirmation') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Ações -->
        <div class="mt-6 space-y-3">
            <button type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2.5 rounded-lg flex items-center justify-center gap-2 transition">
                <i class="fa fa-check"></i>
                {{ $mustChange ? 'Definir Nova Senha' : 'Alterar Senha' }}
            </button>

            <a href="#" wire:click.prevent="logout"
                class="block text-center text-sm text-red-600 hover:underline">
                <i class="fa fa-sign-out-alt mr-1"></i> Sair do Sistema
            </a>

            @unless($mustChange)
                <a href="{{ route('admin.dashboard') }}"
                    class="block text-center text-sm text-blue-700 hover:underline">
                    <i class="fa fa-arrow-left mr-1"></i> Voltar ao Sistema
                </a>
            @endunless
        </div>
    </form>
</div>
