<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batelada extends Model
{
    use HasFactory;

    protected $fillable = [
        'formulacao_id',
        'quantidade_produzida',
        'custo_total',
        'valor_por_kg',
        'data_producao',
    ];

    // Relacionamento com Formulacao
    public function formulacao()
    {
        return $this->belongsTo(Formulacao::class, 'formulacao_id');
    }

    // Relacionamento com Estoque
    public function estoque()
    {
        return $this->hasOne(Estoque::class);
    }
}
