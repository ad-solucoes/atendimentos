<div class="max-w-md mx-auto mt-20 text-center">
    <h2 class="text-2xl font-bold mb-3">Verifique seu email</h2>

    @if (session('message'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-3">{{ session('message') }}</div>
    @endif

    @if($emailSent)
        <!-- Email Sent State -->
        <div class="alert alert-success" role="alert" style="text-align: center;">
            <i class="fa fa-check-circle fa-2x" style="margin-bottom: 10px;"></i>
            <h4 class="m-t-0 m-b-0" style="margin: 10px 0;">✅ Email Enviado!</h4>
            <p style="margin: 10px 0;">
                Um novo email de verificação foi enviado.<br>
                Verifique sua caixa de entrada e spam.
            </p>
        </div>
    @else
        <!-- User Info (initial state) -->
        <div class="alert alert-info" role="alert" style="text-align: center;">
            <div style="margin-bottom: 0px;">
                <i class="fa fa-user-circle fa-3x" style="color: #1976d2;"></i>
            </div>
            <h4 class="m-t-0 m-b-0" style="margin: 10px 0;">Olá, {{ auth()->user()->name }}!</h4>
            <p style="margin: 10px 0;">
                Para acessar o sistema, você precisa verificar seu endereço de email:
            </p>
            <strong style="background: #1976d2; color: white; padding: 8px 15px; border-radius: 5px; display: inline-block; margin-top: 10px;">
                {{ auth()->user()->email }}
            </strong>
        </div>

        <!-- Information -->
        <div class="alert alert-warning" role="alert">
            <h5 class="m-t-0 m-b-1"><i class="fa fa-info-circle"></i> Instruções</h5>
            <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                <li>Um email de verificação foi enviado para seu endereço</li>
                <li>Clique no link no email para verificar sua conta</li>
                <li>O link de verificação expira em 60 minutos</li>
                <li>A verificação garante a segurança da sua conta</li>
            </ul>
        </div>
    @endif

    <p class="mt-5">Um link de verificação foi enviado para seu email.</p>

    <button wire:click="resend" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Reenviar Email</button>
</div>
