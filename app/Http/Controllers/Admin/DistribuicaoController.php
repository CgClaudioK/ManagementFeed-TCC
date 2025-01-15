<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DistribuicaoController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'estoque_id' => 'required|exists:estoques,id',
        'quantidade_retirada' => 'required|numeric|min:1',
    ]);

    $estoque = Estoque::findOrFail($request->estoque_id);

    if ($request->quantidade_retirada > $estoque->quantidade_disponivel) {
        return back()->withErrors(['quantidade_retirada' => 'Quantidade excede o estoque disponível.']);
    }

    // Registrar a distribuição
    $distribuicao = new Distribuicao();
    $distribuicao->estoque_id = $estoque->id;
    $distribuicao->quantidade_retirada = $request->quantidade_retirada;
    $distribuicao->data_distribuicao = now();
    $distribuicao->save();

    // Atualizar o estoque
    $estoque->quantidade_disponivel -= $request->quantidade_retirada;
    $estoque->save();

    return redirect()->route('bateladas.show', $estoque->batelada_id)->with('success', 'Distribuição registrada com sucesso!');
}

}
