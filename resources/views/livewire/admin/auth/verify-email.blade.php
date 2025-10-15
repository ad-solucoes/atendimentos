<div class="w-full text-center">
    <h2 class="text-2xl font-bold text-blue-900 mb-2">Verifique seu Email</h2>
    <p class="text-gray-600 mb-6 text-sm">
        Confirme seu endereço de email para garantir a segurança da sua conta.
    </p>

    @if(session('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
            <i class="fa fa-check-circle mr-2"></i> {{ session('message') }}
        </div>
    @endif

    @if($emailSent)
        <div class="bg-green-50 border border-green-200 text-green-800 p-5 rounded-lg mb-4">
            <i class="fa fa-envelope text-3xl mb-2"></i>
            <h4 class="font-semibold mb-1">Email reenviado com sucesso!</h4>
            <p class="text-xs">Verifique sua caixa de entrada ou pasta de spam.</p>
        </div>

        <a href="#" wire:click.prevent="logout"
            class="inline-flex items-center text-sm text-red-600 hover:underline">
            <i class="fa fa-sign-out-alt mr-1"></i> Sair do Sistema
        </a>
    @else
        <div class="bg-blue-50 border border-blue-200 text-blue-800 p-5 rounded-lg mb-4">
            <i class="fa fa-user-circle text-3xl mb-2"></i>
            <h4 class="font-semibold mb-1">Olá, {{ auth()->user()->name }}!</h4>
            <p class="text-xs">Confirme o email abaixo para continuar:</p>
            <div class="mt-2 bg-blue-700 text-white py-2 px-4 rounded-lg text-sm inline-block">
                {{ auth()->user()->email }}
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-lg text-left text-sm mb-4">
            <h5 class="font-semibold mb-1"><i class="fa fa-info-circle mr-1"></i> Instruções</h5>
            <ul class="list-disc list-inside text-xs text-yellow-700 space-y-1">
                <li>Um email foi enviado para o endereço acima</li>
                <li>Clique no link recebido para validar sua conta</li>
                <li>O link expira em 60 minutos</li>
            </ul>
        </div>

        <button wire:click="resend"
            class="w-full flex items-center justify-center gap-2 bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 rounded-lg transition">
            <i class="fa fa-paper-plane"></i> Reenviar Email de Verificação
        </button>

        <a href="#" wire:click.prevent="logout"
            class="block text-center text-sm text-red-600 hover:underline mt-3">
            <i class="fa fa-sign-out-alt mr-1"></i> Sair do Sistema
        </a>
    @endif
</div>
