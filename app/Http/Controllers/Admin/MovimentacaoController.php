<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MovimentacaoEstoque;

class MovimentacaoController extends Controller
{
    public function historico()
    {
        // Pegando todas as movimentações com relacionamento 'insumo'
        $movimentacoes = MovimentacaoEstoque::with('insumo')->paginate(10);
        return view('admin.insumos.movimentacoes', compact('movimentacoes'));
    }
    
    public function show($id)
    {
        // Acessa a movimentação com o insumo associado
        $movimentacao = MovimentacaoEstoque::with('insumo')->findOrFail($id);
        return view('admin.insumos.movimentacao', compact('movimentacao'));
    }

}
