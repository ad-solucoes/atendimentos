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

        Schema::create('setores', function (Blueprint $table) {
            $table->bigIncrements('setor_id');
            $table->string('setor_nome');
            $table->boolean('setor_status')->default(1);
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();
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
            $table->boolean('atendimento_status')->default(1);
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
            $table->enum('solicitacao_status', [
                'aguardando',   // recebida mas ainda não encaminhada
                'em_andamento', // em análise, ou sendo marcada
                'marcada',      // já possui data agendada
                'realizada',    // procedimento concluído
                'cancelada'     // cancelada ou indeferida
            ])->default('aguardando');
            $table->integer('created_user_id')->nullable();
            $table->integer('updated_user_id')->nullable();
            $table->timestamps();

            $table->foreign('solicitacao_atendimento_id')
                ->references('atendimento_id')->on('atendimentos')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('solicitacao_procedimento_id')
                ->references('procedimento_id')->on('procedimentos')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('solicitacao_localizacao_atual_id')
                ->references('setor_id')->on('setores')
                ->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
        Schema::dropIfExists('atendimentos');
        Schema::dropIfExists('pacientes');
        Schema::dropIfExists('agentes_saude');
        Schema::dropIfExists('equipes_saude');
        Schema::dropIfExists('setores');
        Schema::dropIfExists('procedimentos');
        Schema::dropIfExists('tipos_procedimento');
    }
};
