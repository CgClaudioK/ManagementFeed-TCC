<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receita;

class ReceitaController extends Controller
{
    public function __construct(private Receita $receita)
    {

    }

    public function index()
    {
        $receitas = $this->receita->paginate(10);
        return view('admin.receitas.index', compact('receitas'));
    }

    public function create(){
        return view('admin.receitas.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'insumos' => 'required|array',
        'quantidades' => 'required|array',
    ]);

    $receita = Receita::create([
        'nome' => $request->nome,
        'descricao' => $request->descricao,
    ]);

    foreach ($request->insumos as $index => $insumoId) {
        Receita::create([
            'receita_id' => $receita->id,
            'insumo_id' => $insumoId,
            'quantidade_insumo' => $request->quantidades[$index],
        ]);
    }

    return redirect()->route('admin.receitas.index')->with('success', 'Receita criada com sucesso!');
}

}
