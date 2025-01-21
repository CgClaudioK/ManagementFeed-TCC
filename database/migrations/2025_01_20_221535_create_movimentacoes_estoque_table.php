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
        Schema::create('movimentacoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
            $table->foreignId('produto_id')->nullable()->constrained('produtos')->onDelete('cascade');
            $table->enum('tipo', ['entrada', 'saida']); // Tipo da movimentação
            $table->decimal('quantidade', 10, 2); // Quantidade movimentada
            $table->decimal('valor_unitario', 10, 2); // Valor por kg, no caso de entradas
            $table->decimal('valor_total', 10, 2)->nullable(); // Valor total da movimentação (para entradas)
            $table->timestamp('data_movimentacao'); // Data da movimentação
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_estoque');
    }
};
