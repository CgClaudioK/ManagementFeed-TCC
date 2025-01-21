<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formulacao;

class FormulacaoController extends Controller
{
    public function __construct(private Formulacao $formulacao)
    {

    }

    public function index()
    {
        $formulacoes = $this->formulacao->paginate(10);
        return view('admin.formulacoes.index', compact('formulacoes'));
    }


    public function create()
    {
    $insumos = \App\Models\Insumo::with('produto')->get(); // Carrega os insumos com o produto relacionado
    return view('admin.formulacoes.create', compact('insumos'));
    }
    // public function create(){
    //     return view('admin.formulacoes.create');
    // }

    public function store(Request $request)
    {

    $request->validate([
        'nome' => 'required|string|max:255',
        'insumos' => 'required|array',
        'quantidades' => 'required|array',
    ]);

    $formulacao = Formulacao::create([
        'nome' => $request->nome,
        'descricao' => $request->descricao,
    ]);

    foreach ($request->insumos as $index => $insumoId) {
        $formulacao->insumos()->attach($insumoId, [
            'quantidade' => $request->quantidades[$index],
        ]);
    }
    return redirect()->route('admin.formulacoes.index')->with('success', 'Formulação criada com sucesso!');
    }

    public function formulacoes(Request $request)
    {
    // Validação dos dados
    $validated = $request->validate([
        'tipo_animal' => 'required|string',
        'nome' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'quantidade_total_kg' => 'nullable|numeric|min:0',
        'insumos' => 'required|array',
        'quantidades' => 'required|array',
    ]);

    // Criar uma nova formulação
    $formulacao = Formulacao::create([
        'tipo_animal' => $validated['tipo_animal'],
        'nome' => $validated['nome'],
        'descricao' => $validated['descricao'],
        'quantidade_total_kg' => $validated['quantidade_total_kg'],
    ]);

    // Vincular insumos à formulação
    foreach ($validated['insumos'] as $index => $insumoId) {
        $formulacao->insumos()->attach($insumoId, [
            'quantidade' => $validated['quantidades'][$index],
        ]);
    }

    return redirect()->route('admin.formulacoes.index')
        ->with('success', 'Formulação criada com sucesso!');
    }
    public function edit(Formulacao $formulacao)
{
    $formulacao->load('insumos'); // Garante que os insumos estão carregados
    $insumos = \App\Models\Insumo::with('produto')->get(); // Carrega os insumos com seus produtos
    return view('admin.formulacoes.edit', compact('formulacao', 'insumos'));
}

public function update(Request $request, Formulacao $formulacao)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'tipo_animal' => 'required|string',
        'quantidade_total_kg' => 'nullable|numeric|min:0',
        'insumos' => 'required|array',
        'quantidades' => 'required|array',
    ]);

    $formulacao->update([
        'nome' => $request->nome,
        'descricao' => $request->descricao,
        'tipo_animal' => $request->tipo_animal,
        'quantidade_total_kg' => $request->quantidade_total_kg,
    ]);

    // Atualiza os insumos
    $formulacao->insumos()->detach(); // Remove os insumos antigos
    foreach ($request->insumos as $index => $insumoId) {
        $formulacao->insumos()->attach($insumoId, [
            'quantidade' => $request->quantidades[$index],
        ]);
    }

    return redirect()->route('admin.formulacoes.index')
         ->with('success', 'Formulação atualizada com sucesso!');
}

    public function destroy(Formulacao $formulacao)
    {
    $formulacao->delete();

    return redirect()->route('admin.formulacoes.index')
        ->with('success', 'Formulação excluída com sucesso!');
    }

    public function getInsumos($id)
    {
        // Busca a formulação pelo ID, incluindo insumos e seus respectivos produtos
        $formulacao = Formulacao::with('insumos.produto')->findOrFail($id);
    
        // Mapeia os dados para serem retornados
        $insumos = $formulacao->insumos->map(function ($insumo) {
            return [
                'id' => $insumo->id,
                'produto' => [
                    'id' => $insumo->produto->id,
                    'nome' => $insumo->produto->nome_produto,
                ],
                'quantidade' => $insumo->pivot->quantidade,
                'valor_insumo_kg' => $insumo->valor_insumo_kg,
            ];
        });
    
        // Retorna os dados da formulação e seus insumos
        return response()->json([
            'id' => $formulacao->id,
            'nome' => $formulacao->nome,
            'quantidade_total_kg' => $formulacao->quantidade_total_kg,
            'insumos' => $insumos,
        ]);
    }

}