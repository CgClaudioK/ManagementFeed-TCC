<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulacaoInsumosTable extends Migration
{
    public function up()
    {
        Schema::create('formulacao_insumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulacao_id')
                ->constrained('formulacoes')
                ->onDelete('cascade');
            $table->foreignId('insumo_id')
                ->constrained('insumos')
                ->onDelete('cascade');
            $table->decimal('quantidade', 10, 2)->notNullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formulacao_insumos');
    }
}
