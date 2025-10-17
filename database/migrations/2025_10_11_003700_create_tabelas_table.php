<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('must_change_password')->default(false);
            $table->boolean('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('setores', function (Blueprint $table) {
            $table->bigIncrements('setor_id');
            $table->string('setor_nome');
            $table->boolean('setor_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('tipos_procedimento', function (Blueprint $table) {
            $table->bigIncrements('tipo_procedimento_id');
            $table->string('tipo_procedimento_nome');
            $table->boolean('tipo_procedimento_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('procedimentos', function (Blueprint $table) {
            $table->bigIncrements('procedimento_id');
            $table->unsignedBigInteger('procedimento_tipo_id');
            $table->string('procedimento_nome');
            $table->boolean('procedimento_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();

            $table->foreign('procedimento_tipo_id')
                ->references('tipo_procedimento_id')->on('tipos_procedimento')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('equipes_saude', function (Blueprint $table) {
            $table->bigIncrements('equipe_saude_id');
            $table->string('equipe_saude_nome');
            $table->boolean('equipe_saude_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('agentes_saude', function (Blueprint $table) {
            $table->bigIncrements('agente_saude_id');
            $table->unsignedBigInteger('agente_saude_equipe_saude_id');
            $table->string('agente_saude_nome');
            $table->string('agente_saude_apelido')->nullable();
            $table->boolean('agente_saude_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();

            $table->foreign('agente_saude_equipe_saude_id')
                ->references('equipe_saude_id')->on('equipes_saude')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('pacientes', function (Blueprint $table) {
            $table->bigIncrements('paciente_id');
            $table->unsignedBigInteger('paciente_agente_saude_id')->nullable();
            $table->string('paciente_nome');
            $table->string('paciente_sexo', 10);
            $table->date('paciente_data_nascimento');
            $table->string('paciente_nome_mae', 125)->nullable();
            $table->string('paciente_endereco')->nullable();
            $table->string('paciente_contato', 20)->nullable();
            $table->string('paciente_cns', 20)->nullable();
            $table->string('paciente_cpf', 15)->unique();
            $table->boolean('paciente_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();

            $table->foreign('paciente_agente_saude_id')
                ->references('agente_saude_id')->on('agentes_saude')
                ->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('atendimentos', function (Blueprint $table) {
            $table->bigIncrements('atendimento_id');
            $table->unsignedBigInteger('atendimento_paciente_id');
            $table->enum('atendimento_prioridade', ['Baixa', 'Média', 'Alta'])->default('Baixa');
            $table->string('atendimento_numero', 15)->unique(); // ex: 20251012001
            $table->dateTime('atendimento_data');
            $table->string('atendimento_observacao', 500)->nullable();
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();

            $table->foreign('atendimento_paciente_id')
                ->references('paciente_id')->on('pacientes')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->bigIncrements('solicitacao_id');
            $table->unsignedBigInteger('solicitacao_atendimento_id');
            $table->unsignedBigInteger('solicitacao_procedimento_id');
            $table->unsignedBigInteger('solicitacao_localizacao_atual_id')->nullable();

            $table->string('solicitacao_numero', 20);
            $table->date('solicitacao_data');

            // STATUS textual, mais semântico
            $table->enum('solicitacao_status', [
                'pendente',   // quando inicia a solicitação
                'aguardando',   // aguardando marcação
                'agendado',     // já tem data marcada
                'marcado',      // confirmado/agendado
                'entregue',     // entregue (ao paciente/ACS/equipe)
                'cancelado', // quando não é possível marcar
                'devolvido', // quando o paciente pedi de volta a solicitação
            ])->default('pendente');

            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->unsignedBigInteger('updated_user_id')->nullable();
            $table->timestamps();

            // 🔗 Relacionamentos
            $table->foreign('solicitacao_atendimento_id')
                ->references('atendimento_id')->on('atendimentos')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('solicitacao_procedimento_id')
                ->references('procedimento_id')->on('procedimentos')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('solicitacao_localizacao_atual_id')
                ->references('setor_id')->on('setores')
                ->onDelete('set null')->onUpdate('cascade');

            // 🧑‍💻 opcional: se existir tabela users
            $table->foreign('created_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('solicitacao_movimentacoes', function (Blueprint $table) {
            $table->bigIncrements('movimentacao_id');
            $table->unsignedBigInteger('movimentacao_solicitacao_id');
            $table->unsignedBigInteger('movimentacao_usuario_id')->nullable();
            $table->unsignedBigInteger('movimentacao_destino_id')->nullable(); // setor destino

            // Tipo de movimentação (fluxo operacional)
            $table->enum('movimentacao_tipo', [
                '',
                'encaminhamento', // recepção -> marcação
                'retorno',        // marcação -> recepção
                'entrega',        // entrega ao paciente/ACS/equipe
            ])->default('');

            // Identifica o tipo de pessoa/unidade que recebeu (só usado se tipo == entrega)
            $table->enum('movimentacao_entregue_para', [
                '',
                'paciente',
                'agente_saude',
                'equipe_saude',
            ])->nullable();

            $table->text('movimentacao_observacao')->nullable();
            $table->timestamp('movimentacao_data')->useCurrent();
            $table->timestamps();

            $table->foreign('movimentacao_solicitacao_id')
                ->references('solicitacao_id')->on('solicitacoes')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('movimentacao_usuario_id')
                ->references('id')->on('users')
                ->onDelete('set null')->onUpdate('cascade');

            $table->foreign('movimentacao_destino_id')
                ->references('setor_id')->on('setores')
                ->onDelete('set null')->onUpdate('cascade');

            // 🔍 para relatórios rápidos
            $table->index('movimentacao_solicitacao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacao_movimentacoes');
        Schema::dropIfExists('solicitacoes');
        Schema::dropIfExists('atendimentos');
        Schema::dropIfExists('pacientes');
        Schema::dropIfExists('agentes_saude');
        Schema::dropIfExists('equipes_saude');
        Schema::dropIfExists('procedimentos');
        Schema::dropIfExists('tipos_procedimento');
        Schema::dropIfExists('setores');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
