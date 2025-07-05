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
        Schema::create('patrimonios', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('numero_patrimonio')->unique();
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->date('data_aquisicao')->nullable();
            $table->decimal('valor_aquisicao', 10, 2)->nullable();
            $table->string('localizacao')->nullable();
            $table->string('responsavel')->nullable();
            $table->string('estado_conservacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrimonios');
    }
};
