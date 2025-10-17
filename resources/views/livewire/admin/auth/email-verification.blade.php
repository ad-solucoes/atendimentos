<div class="w-full text-center">
    <h2 class="text-2xl font-bold text-blue-900 mb-4">
        {{ $verified ? 'Email Exibirificado!' : ($error ? 'Erro na Exibirificação' : 'Exibirificando Email...') }}
    </h2>

    <p class="text-gray-600 mb-6 text-sm">
        @if ($verified)
            Seu email foi verificado com sucesso.
        @elseif($error)
            Não foi possível verificar seu email. Tente novamente ou solicite um novo link.
        @else
            Aguarde enquanto confirmamos a verificação...
        @endif
    </p>

    {{-- Estado: sucesso --}}
    @if ($verified)
        <div class="bg-green-50 border border-green-300 text-green-800 p-5 rounded-lg mb-4">
            <i class="fa fa-check-circle text-3xl mb-2"></i>
            <h4 class="font-semibold mb-1">{{ $message }}</h4>
            <p class="text-xs">Agora você pode acessar todas as funcionalidades do sistema com segurança.</p>
        </div>

        <button wire:click="continueToSystem"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2 transition">
            <i class="fa fa-arrow-right"></i> Acessar Sistema
        </button>

        <div class="mt-6 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-4 text-left text-sm">
            <h5 class="font-semibold mb-1"><i class="fa fa-shield mr-1"></i> Conta Protegida</h5>
            <ul class="list-disc list-inside text-xs space-y-1 text-blue-700">
                <li>Email verificado com sucesso</li>
                <li>Conta protegida contra acesso não autorizado</li>
                <li>Recuperação de senha habilitada</li>
            </ul>
        </div>
    @endif

    {{-- Estado: erro --}}
    @if ($error)
        <div class="bg-red-50 border border-red-300 text-red-800 p-5 rounded-lg mb-4">
            <i class="fa fa-times-circle text-3xl mb-2"></i>
            <h4 class="font-semibold mb-1">{{ $message }}</h4>
            <p class="text-xs">Possíveis causas: link expirado, já utilizado ou inválido.</p>
        </div>

        <button wire:click="resendExibirificationEmail"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg flex items-center justify-center gap-2 transition">
            <i class="fa fa-envelope"></i> Solicitar Novo Email
        </button>

        <a href="#" wire:click.prevent="logout"
            class="block text-center text-sm text-red-600 hover:underline mt-3">
            <i class="fa fa-sign-out-alt mr-1"></i> Sair do Sistema
        </a>

        <div class="mt-6 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 text-left text-sm">
            <h5 class="font-semibold mb-1"><i class="fa fa-exclamation-triangle mr-1"></i> Problemas Comuns</h5>
            <ul class="list-disc list-inside text-xs space-y-1 text-yellow-700">
                <li>Link de verificação expirado (60 minutos)</li>
                <li>Link já utilizado anteriormente</li>
                <li>URL modificada ou corrompida</li>
                <li>Problema temporário no sistema</li>
            </ul>
        </div>
    @endif

    {{-- Estado: carregando --}}
    @if (!$verified && !$error)
        <div class="bg-blue-50 border border-blue-200 text-blue-800 p-5 rounded-lg">
            <i class="fa fa-spinner fa-spin text-3xl mb-2"></i>
            <h4 class="font-semibold mb-1">Exibirificando seu email...</h4>
            <p class="text-xs">Aguarde enquanto processamos a verificação.</p>
        </div>
    @endif
</div>
