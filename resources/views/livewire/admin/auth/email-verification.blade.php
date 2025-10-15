<div>
    <div class="signin-text">
        @if($verified)
            <span>✅ Email Verificado!</span>
        @elseif($error)
            <span>❌ Erro na Verificação</span>
        @else
            <span>Verificando Email</span>
        @endif
    </div>

    <p style="text-align: center; color: #666; margin-bottom: 20px;">
        @if($verified)
            Seu email foi verificado com sucesso
        @elseif($error)
            Não foi possível verificar seu email
        @else
            Processando verificação...
        @endif
    </p>

    @if($verified)
        <!-- Success State -->
        <div class="alert alert-success" role="alert" style="text-align: center;">
            <i class="fa fa-check-circle fa-3x" style="margin-bottom: 15px;"></i>
            <h4 style="margin: 10px 0;">{{ $message }}</h4>
            <p style="margin: 10px 0;">
                Agora você pode acessar todas as funcionalidades do sistema com segurança.
            </p>
        </div>

        <div class="form-actions">
            <button type="button" wire:click="continueToSystem" class="signin-btn bg-primary">
                <i class="fa fa-arrow-right"></i> Acessar Sistema
            </button>
        </div>

        <!-- Info Section -->
        <div class="alert alert-info" role="alert" style="margin-top: 15px;">
            <h5 style="margin: 0 0 10px 0;"><i class="fa fa-shield"></i> Conta Protegida</h5>
            <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                <li>Email verificado com sucesso</li>
                <li>Conta protegida contra acesso não autorizado</li>
                <li>Recuperação de senha habilitada</li>
            </ul>
        </div>

    @elseif($error)
        <!-- Error State -->
        <div class="alert alert-danger" role="alert" style="text-align: center;">
            <i class="fa fa-times-circle fa-3x" style="margin-bottom: 15px;"></i>
            <h4 style="margin: 10px 0;">{{ $message }}</h4>
            <p style="margin: 10px 0;">
                Possíveis causas: link expirado, já utilizado ou inválido.
            </p>
        </div>

        <div class="form-actions">
            <button type="button" wire:click="resendVerificationEmail" class="signin-btn bg-primary">
                <i class="fa fa-envelope"></i> Solicitar Novo Email
            </button>

            <a href="#" wire:click.prevent="logout" class="forgot-password" id="forgot-password-link"><i class="fa fa-sign-out fa-fw"></i> Sair do Sistema</a>
        </div>

        <!-- Problems Section -->
        <div class="alert alert-warning" role="alert" style="margin-top: 15px;">
            <h5 style="margin: 0 0 10px 0;"><i class="fa fa-exclamation-triangle"></i> Problemas Comuns</h5>
            <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                <li>Link de verificação expirado (60 minutos)</li>
                <li>Link já utilizado anteriormente</li>
                <li>URL modificada ou corrompida</li>
                <li>Problema temporário no sistema</li>
            </ul>
        </div>

    @else
        <!-- Loading State -->
        <div class="alert alert-info" role="alert" style="text-align: center;">
            <i class="fa fa-spinner fa-spin fa-3x" style="margin-bottom: 15px;"></i>
            <h4 style="margin: 10px 0;">Verificando seu email...</h4>
            <p style="margin: 10px 0;">
                Aguarde enquanto processamos a verificação.
            </p>
        </div>
    @endif
</div>
