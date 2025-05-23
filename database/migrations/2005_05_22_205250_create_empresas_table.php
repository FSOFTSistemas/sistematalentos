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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 20)->nullable();
            $table->foreignId('plano_id')->constrained('planos');
            $table->date('data_inicio_plano')->nullable();
            $table->date('data_fim_plano')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('email')->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('responsavel')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
