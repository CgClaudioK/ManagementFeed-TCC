<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{
    public function create()
    {
    return view('admin.produtos.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nome_produto' => 'required|string|max:255',
            'nome_comercial' => 'required|string|max:255',
        ]);

        Produto::create([
            'nome_produto' => $request->nome_produto,
            'nome_comercial' => $request->nome_comercial,
        ]);

        return redirect()->route('admin.insumos.create')->with('success', 'Produto cadastrado com sucesso!');
    }
    public function destroy($id)
    {
        // Buscar o produto pelo ID
        $produto = Produto::findOrFail($id);
        
        // Excluir o produto
        $produto->delete();

        // Redirecionar com mensagem de sucesso
        return redirect()->route('admin.produtos.index')->with('success', 'Produto excluído com sucesso!');
    }

}
