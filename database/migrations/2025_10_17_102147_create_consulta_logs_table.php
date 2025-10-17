<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultas_log', function (Blueprint $table) {
            $table->id();
            $table->string('ip_usuario')->nullable();
            $table->string('cpf')->nullable();
            $table->string('numero_atendimento')->nullable();
            $table->timestamp('consultado_em')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas_log');
    }
};
