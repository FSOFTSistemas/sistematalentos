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
    Schema::table('patrimonios', function (Blueprint $table) {
        $table->foreignId('empresa_id')->nullable()->after('id')->constrained()->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('patrimonios', function (Blueprint $table) {
        $table->dropForeign(['empresa_id']);
        $table->dropColumn('empresa_id');
    });
}
};
