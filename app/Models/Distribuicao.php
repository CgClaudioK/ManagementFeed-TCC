<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribuicao extends Model
{
    use HasFactory;

    protected $table = 'distribuicoes';

    protected $fillable = [
        'batelada_id',
        'quantidade_distribuida',
        'data_distribuicao',
    ];

    // Relacionamento com Batelada
    public function batelada()
    {
        return $this->belongsTo(Batelada::class);
    }
}
