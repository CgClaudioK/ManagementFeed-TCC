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
    Schema::create('distribuicoes', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('estoque_id');
        $table->decimal('quantidade_retirada', 10, 2);
        $table->date('data_distribuicao');
        $table->timestamps();

        $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribuicoes');
    }
};
