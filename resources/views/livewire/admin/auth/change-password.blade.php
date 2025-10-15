<div>
    <div class="signin-text">
        @if($mustChange)
            <span>Primeira Senha</span>
        @else
            <span>Alterar Senha</span>
        @endif
    </div> <!-- / .signin-text -->

    <p style="text-align: center; color: #666; margin-bottom: 20px;">
        @if($mustChange)
            Por segurança, altere sua senha temporária para continuar
        @else
            Mantenha sua conta segura atualizando sua senha
        @endif
    </p>

    @if($mustChange)
        <!-- Warning for mandatory change - Usando classes Bootstrap -->
        <div class="alert alert-warning" role="alert">
            <h5 class="m-t-0 m-b-1"><i class="fa fa-exclamation-triangle"></i> Alteração Obrigatória</h5>
            <p style="margin: 0; font-size: 12px;">
                Por segurança, você deve alterar sua senha temporária antes de acessar o sistema.
            </p>
        </div>
    @endif

    <form wire:submit.prevent="updatePassword" autocomplete="off">
        @csrf

        <div class="form-group">
            <label class="control-label">Senha Atual</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" wire:model="current_password" class="form-control" placeholder="Digite sua senha atual">
            </div>
            <small class="help-block">Confirme sua identidade</small>
            @error('current_password')
                <span class="help-block text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="control-label">Nova Senha</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" wire:model="password" class="form-control" placeholder="Digite sua nova senha">
            </div>
            <small class="help-block">Mínimo 8 caracteres com letras, números e símbolos</small>
            @error('password')
                <span class="help-block text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="control-label">Confirmar Nova Senha</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-check-circle"></i></span>
                <input type="password" wire:model="password_confirmation" class="form-control" placeholder="Confirme sua nova senha">
            </div>
            <small class="help-block">Digite a mesma senha novamente</small>
            @error('password_confirmation')
                <span class="help-block text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Security Tips - Usando classes Bootstrap para consistência -->
        <div class="alert alert-success" role="alert">
            <h5 style="margin: 0 0 10px 0;"><i class="fa fa-shield"></i> Dicas de Segurança</h5>
            <ul style="margin: 0; padding-left: 20px; font-size: 12px;">
                <li>Use pelo menos 8 caracteres</li>
                <li>Combine letras maiúsculas e minúsculas</li>
                <li>Inclua números e símbolos</li>
                <li>Evite informações pessoais</li>
                <li>Use senhas diferentes para cada serviço</li>
            </ul>
        </div>

        <div class="form-actions"> {{-- Agrupando botões e links em form-actions --}}
            <button type="submit" class="signin-btn bg-primary"> {{-- Usando classe do botão de login --}}
                <i class="fa fa-check"></i>
                {{ $mustChange ? 'Definir Nova Senha' : 'Alterar Senha' }}
            </button>

            <a href="#" wire:click.prevent="logout" class="forgot-password" id="forgot-password-link"><i class="fa fa-sign-out fa-fw"></i> Sair do Sistema</a>

            @if(!$mustChange)
                <a href="{{ route('dashboard') }}" class="forgot-password" style="margin-top: 10px;"> {{-- Usando classe de link existente --}}
                    <i class="fa fa-arrow-left"></i> Voltar ao Sistema
                </a>
            @endif
        </div>
    </form>
</div>
