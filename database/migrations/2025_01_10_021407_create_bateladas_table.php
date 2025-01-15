<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
    Schema::create('bateladas', function (Blueprint $table) {
        $table->id();
        $table->Integer('formulacao_id');
        $table->decimal('quantidade_produzida', 10, 2);
        $table->decimal('custo_total', 10, 2);
        $table->decimal('valor_por_kg', 10, 2);
        $table->date('data_producao');
        $table->timestamps();

        $table->foreign('formulacao_id')->references('id')->on('formulacoes')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bateladas');
    }
};
