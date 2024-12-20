<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsumosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insumos', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->int('id_produto'); // Nome do produto
            $table->string('unidade'); // Unidade
            $table->decimal('quantidade_insumo', 10, 4); // Quantidade disponível
            $table->decimal('valor_insumo_kg', 10, 2); // Valor do insumo/kg
            $table->decimal('valor_unitario', 10, 2); // Valor unitário
            $table->decimal('valor_total', 10, 2); // Valor do insumo
            $table->decimal('kg_insumo_total', 10, 2); // KG do insumo
            $table->timestamps(); // Colunas 'created_at' e 'updated_at'
            $table->foreign('nome_produto')->references('id')->on('produtos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insumos');
    }
};