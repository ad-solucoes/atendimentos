<div class="max-w-md mx-auto mt-20 text-center">
    <h2 class="text-2xl font-bold mb-3">Verifique seu email</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">{{ session('message') }}</div>
    @endif

    <p>Um link de verificação foi enviado para seu email.</p>

    <button wire:click="resend" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Reenviar Email</button>
</div>
