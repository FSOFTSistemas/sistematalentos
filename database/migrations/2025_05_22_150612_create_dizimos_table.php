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
        Schema::create('dizimos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained()->onDelete('cascade');
            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->string('mes_referencia');
            $table->integer('ano_referencia');
            $table->foreignId('caixa_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dizimos');
    }
};
