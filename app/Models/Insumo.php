<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    // Permite a atribuição em massa apenas dos campos listados aqui
    protected $fillable = [
        'id_produto', 
        'unidade',
        'quantidade_insumo', 
        'valor_insumo_kg', 
        'valor_unitario', 
        'valor_total', 
        'kg_insumo_total',
    ];

    public function formulacoes()
    {
        return $this->belongsToMany(Formulacao::class)->withPivot('quantidade');
    }

    public function produto()
{
    return $this->belongsTo(Produto::class, 'id_produto', 'id');
}
}
