<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Formulacao extends Model
{
    use HasFactory;

    protected $table = 'formulacoes';
    protected $fillable = [
        'tipo_animal', 'nome', 'descricao', 'quantidade_total_kg'
    ];

    public function insumos()
    {
        
        // return $this->belongsToMany(Insumo::class, 'formulacao_insumos', 'formulacao_id', 'insumo_id')
        //             ->withPivot('quantidade')
        //             ->where('insumos.id_produto', '=', \DB::raw('formulacao_insumos.insumo_id'));
        return $this->belongsToMany(Insumo::class, 'formulacao_insumos', 'formulacao_id', 'insumo_id')
                ->withPivot('quantidade');
    }
}
