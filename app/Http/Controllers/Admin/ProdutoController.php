<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProdutoFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produto;

class ProdutoController extends Controller
{

    public function index()
    {
        // Recupera todos os produtos do banco de dados
        $produtos = Produto::paginate(10);

        // Retorna a view com os produtos
        return view('admin.produtos.index', compact('produtos'));
    }
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

    public function edit(string $produto)
    {
        $produto = Produto::findOrFail($produto); // Busca o produto

        return view('admin.produtos.edit', compact('produto'));
    }

    public function update(string $produto, ProdutoFormRequest $request)
    {
        $produto = Produto::findOrFail($produto); // Busca o produto pelo ID

        $produto->update($request->all());

        return redirect()->route('admin.produtos.edit', $produto->id)
            ->with('success', 'Registro atualizado com sucesso!');
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
