<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function relatorio()
    {
        // $bateladas = DB::table('bateladas as b')
        //     ->join('formulacoes as f', 'b.formulacao_id', '=', 'f.id')
        //     ->join('formulacao_insumos as fi', 'f.id', '=', 'fi.formulacao_id')
        //     ->join('insumos as i', 'fi.insumo_id', '=', 'i.id_produto')
        //     ->join('produtos as p', 'i.id_produto', '=', 'p.id')
        //     ->select(
        //         'b.id as batelada_id',
        //         'b.formulacao_id',
        //         'b.quantidade_produzida',
        //         'b.custo_total',
        //         'b.valor_por_kg',
        //         'b.data_producao',
        //         DB::raw("GROUP_CONCAT(p.nome_produto ORDER BY p.nome_produto ASC SEPARATOR ', ') as produtos"),
        //         DB::raw("GROUP_CONCAT(fi.quantidade ORDER BY p.nome_produto ASC SEPARATOR ', ') as quantidades")
        //     )
        //     ->groupBy('b.id', 'b.formulacao_id', 'b.quantidade_produzida', 'b.custo_total', 'b.valor_por_kg', 'b.data_producao')
        //     ->get();

        return view('admin.bateladas.relatorio', compact('batelada'));
    }
}
