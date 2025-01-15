<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulacoesTable extends Migration
{
    public function up()
    {
        Schema::create('formulacoes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_animal', [
                'insetos', 'aves', 'avestruzes', 'bovinos_corte', 'bovinos_leiteiros',
                'camaroes', 'caprinos', 'codornas', 'cunicultura', 'equinos',
                'ovinos', 'peixes', 'pets', 'pombos', 'repteis', 'suinos'
            ])->notNullable();
            $table->string('nome')->notNullable();
            $table->text('descricao')->nullable();
            $table->decimal('quantidade_total_kg', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formulacoes');
    }
}
