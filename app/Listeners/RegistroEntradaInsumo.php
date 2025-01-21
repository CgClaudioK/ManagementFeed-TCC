<?php

namespace App\Listeners;

use App\Models\MovimentacaoEstoque;
use Illuminate\Database\Eloquent\Model;

class RegistroEntradaInsumo
{
    /**
     * Handle the event.
     *
     * @param  Model  $insumo
     * @return void
     */
    public function handle(Model $insumo)
    {
        MovimentacaoEstoque::create([
            'insumo_id' => $insumo->id,
            'tipo_movimentacao' => 'entrada',
            'quantidade' => $insumo->kg_insumo_total,
            'data_movimentacao' => now(),
        ]);
    }
}
