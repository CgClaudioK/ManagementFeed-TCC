<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = ['nome_produto', 'nome_comercial'];

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'id_produto');
    }
}

