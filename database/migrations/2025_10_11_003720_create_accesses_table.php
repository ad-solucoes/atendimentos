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
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('email', 125);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('action', 50)->default('login_attempt');
            $table->boolean('success')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamp('created_at');

            // Índices para performance
            $table->index(['action', 'success']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['user_id', 'success']);
            $table->index('created_at');
            $table->index('email');

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesses');
    }
};
