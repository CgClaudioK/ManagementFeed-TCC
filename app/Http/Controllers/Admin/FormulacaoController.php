<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formulacao;
use Illuminate\Support\Facades\DB;


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
        // $formulacao = Formulacao::with('insumos.produto')->find($id);
        $formulacao = DB::table('formulacoes as f')
            ->join('formulacao_insumos as fi', 'fi.formulacao_id', '=', 'f.id')
            ->join('insumos as i', 'i.id_produto', '=', 'fi.insumo_id')
            ->join('produtos as p', 'p.id', '=', 'fi.insumo_id')
            ->where('formulacao_id', '=',$id)
            ->get('*');

        // dd($formulacao);

        $insumos = $formulacao->map(function ($insumo) {
            return [
                'id' => $insumo->id,
                'produto' => [
                    'id' => $insumo->id,
                    'nome_produto' => $insumo->nome_produto,
                ],
                'quantidade' => $insumo->quantidade,
                'valor_insumo_kg' => $insumo->valor_insumo_kg, // Valor associado ao id_produto
            ];
        });

// Verificar saída

       // Verifica se há insumos antes de tentar acessar dados
        if ($insumos->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum insumo encontrado para a formulação.',
            ], 404);
        }

        // Retorna os dados da formulação e seus insumos
        return response()->json([
            'id' => $formulacao->first()->id ?? null, // Assume que o ID da formulação é o mesmo para todos os insumos
            'nome' => $formulacao->first()->nome_produto ?? null, // Adapte o nome do campo real
            'quantidade_total_kg' => $formulacao->sum('quantidade'),
            'insumos' => $insumos,
        ]);
    }

}