<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = [
        'batelada_id',
        'quantidade_disponivel',
    ];

    // Relacionamento com Batelada
    public function batelada()
    {
        return $this->belongsTo(Batelada::class);
    }
}
