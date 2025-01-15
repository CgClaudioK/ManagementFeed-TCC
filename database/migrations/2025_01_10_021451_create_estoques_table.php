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
    Schema::create('estoques', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('batelada_id');
        $table->decimal('quantidade_disponivel', 10, 2);
        $table->timestamps();

        $table->foreign('batelada_id')->references('id')->on('bateladas')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoques');
    }
};
