<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoEstoque extends Model
{
    use HasFactory;

    // Definindo a tabela associada ao modelo
    protected $table = 'movimentacao_estoque';

    // Definindo os campos que podem ser preenchidos em massa
    protected $fillable = [
        'insumo_id',
        'id_produto',
        'tipo',
        'quantidade',
        'valor_unitario',
        'valor_total',
        'data_movimentacao',
    ];

    // Definindo o relacionamento com o modelo Insumo
    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
