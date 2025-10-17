<?php

declare(strict_types = 1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Site
Route::name('site.')->group(function () {
    Route::get('/', App\Livewire\Site\Inicio::class)->name('inicio');
    Route::get('/buscar', App\Livewire\Site\Buscar::class)->name('buscar');
    Route::get('/documentos/{document}', App\Livewire\Site\DocumentoDetalhes::class)->name('documentos.show');
    Route::get('/sobre', App\Livewire\Site\Sobre::class)->name('sobre');
});

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Rotas públicas
    Route::get('/login', App\Livewire\Admin\Auth\Login::class)->name('login')->middleware('guest');
    Route::get('/forgot-password', App\Livewire\Admin\Auth\ForgotPassword::class)->name('password.request')->middleware('guest');
    Route::get('/reset-password/{token}', App\Livewire\Admin\Auth\ResetPassword::class)->name('password.reset')->middleware('guest');
    // Route::get('/email/verify', App\Livewire\Admin\Auth\VerifyEmail::class)->middleware('auth')->name('verification.notice');

    Route::get('/auth/verify-email', App\Livewire\Admin\Auth\VerifyEmail::class)->middleware('auth')->name('verification.notice');
    Route::get('/auth/email-verification/{id}/{hash}', App\Livewire\Admin\Auth\EmailVerification::class)
        ->middleware(['signed'])
        ->name('verification.verify');
    Route::get('/auth/change-password', App\Livewire\Admin\Auth\ChangePassword::class)->name('password.change');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('logout');

    // Rotas Autenticadas
    Route::middleware(['auth', 'verified', 'password.change'])->group(function () {
        Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/pesquisar', App\Livewire\Admin\Pesquisar::class)->name('pesquisar');

        Route::middleware(['role:Recepcao|Marcacao|Gestor'])->group(function () {
            Route::prefix('setores')->name('setores.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Setores\Listagem::class)->name('listagem')->middleware('permission:Listar Setor');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Setores\Formulario::class)->name('formulario')->middleware('permission:Adicionar Setor|Editar Setor');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Setores\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Setor');
            });

            Route::prefix('tipos_procedimento')->name('tipos_procedimento.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\TiposProcedimento\Listagem::class)->name('listagem')->middleware('permission:Listar Tipo de Procedimento');
                Route::get('/formulario/{id?}', App\Livewire\Admin\TiposProcedimento\Formulario::class)->name('formulario')->middleware('permission:Adicionar Tipo de Procedimento|Editar Tipo de Procedimento');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\TiposProcedimento\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Tipo de Procedimento');
            });

            Route::prefix('procedimentos')->name('procedimentos.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Procedimentos\Listagem::class)->name('listagem')->middleware('permission:Listar Procedimento');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Procedimentos\Formulario::class)->name('formulario')->middleware('permission:Adicionar Procedimento|Editar Procedimento');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Procedimentos\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Procedimento');
            });

            Route::prefix('equipes_saude')->name('equipes_saude.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\EquipesSaude\Listagem::class)->name('listagem')->middleware('permission:Listar Equipe de Saude');
                Route::get('/formulario/{id?}', App\Livewire\Admin\EquipesSaude\Formulario::class)->name('formulario')->middleware('permission:Adicionar Equipe de Saude|Editar Equipe de Saude');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\EquipesSaude\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Equipe de Saude');
            });

            Route::prefix('agentes_saude')->name('agentes_saude.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\AgentesSaude\Listagem::class)->name('listagem')->middleware('permission:Listar Agente de Saude');
                Route::get('/formulario/{id?}', App\Livewire\Admin\AgentesSaude\Formulario::class)->name('formulario')->middleware('permission:Adicionar Agente de Saude|Editar Agente de Saude');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\AgentesSaude\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Agente de Saude');
            });

            Route::prefix('pacientes')->name('pacientes.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Pacientes\Listagem::class)->name('listagem')->middleware('permission:Listar Paciente');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Pacientes\Formulario::class)->name('formulario')->middleware('permission:Adicionar Paciente|Editar Paciente');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Pacientes\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Paciente');
                Route::get('/impressao/{id?}', [App\Http\Controllers\Admin\Pacientes\ImpressaoController::class, 'gerarPdf'])->name('impressao');
            });

            Route::prefix('atendimentos')->name('atendimentos.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Atendimentos\Listagem::class)->name('listagem')->middleware('permission:Listar Atendimento');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Atendimentos\Formulario::class)->name('formulario')->middleware('permission:Adicionar Atendimento|editar Atendimento');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Atendimentos\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Atendimento');
                Route::get('/impressao/{id?}', [App\Http\Controllers\Admin\AtendimentosController::class, 'gerarPdf'])->name('impressao');

                Route::get('/enviar-contato/{id}', [App\Http\Controllers\Admin\AtendimentosController::class, 'enviarParaContato'])->name('enviar-contato');
            });

            Route::prefix('solicitacoes')->name('solicitacoes.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Solicitacoes\Listagem::class)->name('listagem')->middleware('permission:Listar Solicitacao');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Solicitacoes\Formulario::class)->name('formulario')->middleware('permission:Adicionar Solicitacao|Editar Solicitacao');
                Route::get('/detalhes/{id}', App\Livewire\Admin\Solicitacoes\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Solicitacao');
                Route::get('/movimentar/{solicitacao_id}', App\Livewire\Admin\Solicitacoes\Movimentar::class)->name('movimentar')->middleware('permission:Movimentar Solicitacao');

                Route::get('/enviar-contato/{id}', [App\Http\Controllers\Admin\SolicitacoesController::class, 'enviarParaPaciente'])->name('enviar-contato');
            });

            Route::prefix('movimentacoes')->name('movimentacoes.')->group(function () {
                Route::get('/formulario/{id?}', App\Livewire\Admin\Movimentacoes\Formulario::class)->name('formulario')->middleware('permission:Realizar Movimentacao');
                Route::get('/historico/{movimentacao?}', App\Livewire\Admin\Movimentacoes\Formulario::class)->name('historico')->middleware('permission:Historico Movimentacao');
            });

            Route::get('/relatorios', \App\Livewire\Admin\Relatorios\Index::class)->name('relatorios');
        });

        Route::middleware(['role:Administrador'])->group(function () {
            Route::prefix('permissoes')->name('permissoes.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Permissoes\Listagem::class)->name('listagem')->middleware('permission:Listar Permissao');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Permissoes\Formulario::class)->name('formulario')->middleware('permission:Adicionar Permissao|Editar Permissao');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Permissoes\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Permissao');
            });

            Route::prefix('perfis')->name('perfis.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Perfis\Listagem::class)->name('listagem')->middleware('permission:Listar Perfil');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Perfis\Formulario::class)->name('formulario')->middleware('permission:Adicionar Perfil|Editar Perfil');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Perfis\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Perfil');
                Route::get('/permissoes/{id?}', App\Livewire\Admin\Perfis\Permissoes::class)->name('permissoes')->middleware('permission:Gerenciar Permissao');
            });

            Route::prefix('usuarios')->name('usuarios.')->group(function () {
                Route::get('/listagem', App\Livewire\Admin\Usuarios\Listagem::class)->name('listagem')->middleware('permission:Listar Usuario');
                Route::get('/formulario/{id?}', App\Livewire\Admin\Usuarios\Formulario::class)->name('formulario')->middleware('permission:Adicionar Usuario|Editar Usuario');
                Route::get('/detalhes/{id?}', App\Livewire\Admin\Usuarios\Detalhes::class)->name('detalhes')->middleware('permission:Exibir Usuario');
                Route::get('/perfis/{id?}', App\Livewire\Admin\Usuarios\Perfis::class)->name('perfis')->middleware('permission:Gerenciar Perfil');
            });
        });
    });
});
