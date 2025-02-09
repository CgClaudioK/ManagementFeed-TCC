<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsumoFormRequest;
use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\Produto;
use App\Models\MovimentacaoEstoque;

use DB;

class InsumoController extends Controller
{
    public function __construct(private Insumo $insumo)
    {
    }

    public function index(Request $request)
    {
        $ano = $request->input('ano', date('Y')); // Ano escolhido ou ano atual

        // Recupera os insumos para a tabela principal
        $insumos = DB::table('insumos')
            ->join('produtos', 'insumos.id_produto', '=', 'produtos.id')
            ->select(
                'insumos.id as insumo_id',
                'insumos.id_produto',
                'insumos.unidade',
                'insumos.quantidade_insumo',
                'insumos.valor_insumo_kg',
                'insumos.valor_unitario',
                'insumos.valor_total',
                'insumos.kg_insumo_total',
                'insumos.created_at',
                'produtos.nome_produto',
                'produtos.nome_comercial'
            )
            ->paginate(10);

        // Relatório consolidado por produto
        $relatorio = DB::table('insumos')
            ->join('produtos', 'insumos.id_produto', '=', 'produtos.id')
            ->leftJoin('movimentacao_estoque', 'insumos.id', '=', 'movimentacao_estoque.insumo_id')
            ->select(
                'produtos.nome_produto',
                DB::raw('SUM(insumos.kg_insumo_total) as total_quantidade'),
                DB::raw('SUM(insumos.valor_total) as total_gasto'),
                DB::raw('SUM(insumos.valor_total) / SUM(insumos.kg_insumo_total) as preco_medio'),
                DB::raw('COALESCE(SUM(CASE WHEN movimentacao_estoque.tipo = "entrada" THEN movimentacao_estoque.quantidade ELSE 0 END), 0) 
                      - COALESCE(SUM(CASE WHEN movimentacao_estoque.tipo = "saida" THEN movimentacao_estoque.quantidade ELSE 0 END), 0) 
                      as estoque_disponivel')
            )
            ->whereYear('insumos.created_at', $ano) // Filtrar por ano
            ->groupBy('produtos.nome_produto')
            ->get();

        return view('admin.insumos.index', compact('insumos', 'relatorio', 'ano'));
    }

    public function show($id)
{
    $insumo = Insumo::with('produto')->findOrFail($id);
    return view('admin.insumos.show', compact('insumo'));
}

    public function create(){
        $produtos = Produto::all();
        return view('admin.insumos.create', compact('produtos'));
    }

    public function store(InsumoFormRequest $request)
    {
        // Validar os dados recebidos
        $validated = $request->validate([
            'id_produto' => 'required|exists:produtos,id',
            'unidade' => 'required|string|max:50',
            'quantidade_insumo' => 'required|numeric|min:0',
            'valor_insumo_kg' => 'required|string',
            'valor_unitario' => 'required|string',
            'valor_total' => 'nullable|string',
            'kg_insumo_total' => 'nullable|numeric|min:0',
        ]);

        // Normalizar os valores monetários
        $validated['valor_insumo_kg'] = str_replace(',', '.', str_replace(['R$', ' '], '', $validated['valor_insumo_kg']));
        $validated['valor_unitario'] = str_replace(',', '.', str_replace(['R$', ' '], '', $validated['valor_unitario']));
        $validated['valor_total'] = isset($validated['valor_total'])
            ? str_replace(',', '.', str_replace(['R$', ' '], '', $validated['valor_total']))
            : null;

        // Salvar no banco
        $insumo = Insumo::create($validated);

        // Registrar a movimentação de entrada
        MovimentacaoEstoque::create([
            'insumo_id' => $insumo->id,
            'id_produto' => $validated['id_produto'],
            'tipo' => 'entrada',
            'quantidade' => $validated['quantidade_insumo'],
            'valor_unitario' => $validated['valor_unitario'],
            'valor_total' => $validated['quantidade_insumo'] * $validated['valor_unitario'],
            'data_movimentacao' => now(),
        ]);

        return redirect()->route('admin.insumos.index')->with('success', 'Insumo cadastrado e movimentação registrada com sucesso!');
    }

    public function edit(string $insumo)
    {
        $insumo = $this->insumo->findOrFail($insumo); // Busca o insumo
        $produtos = Produto::all(); // Busca todos os produtos

        return view('admin.insumos.edit', compact('insumo', 'produtos'));
    }

    public function update(string $insumo, InsumoFormRequest $request)
    {
        $insumo = $this->insumo->findOrFail($insumo);

        $insumo->update($request->all());

        return redirect()->route('admin.insumos.edit', $insumo->id)
        ->with('success', 'Registro atualizado com sucesso!');
    }

    public function destroy(string $insumo){

        $insumo = Insumo::findOrFail($insumo);
        $insumo->delete();

        return redirect()->back();
    }

    public function registrarEntrada(Request $request, $insumoId)
    {
    $validated = $request->validate([
        'quantidade' => 'required|numeric|min:0.01',
        'valor_unitario' => 'required|numeric|min:0',
    ]);
    $insumo = Insumo::findOrFail($insumoId);

    // Atualiza o estoque do insumo
    $insumo->kg_insumo_total += $validated['quantidade'];
    $insumo->valor_total = $insumo->kg_insumo_total * $validated['valor_unitario'];
    $insumo->save();
    // Registra a movimentação de entrada
    MovimentacaoEstoque::create([
        'insumo_id' => $insumo->id,
        'tipo' => 'entrada',
        'quantidade' => $validated['quantidade'],
        'valor_unitario' => $validated['valor_unitario'],
        'valor_total' => $validated['quantidade'] * $validated['valor_unitario'],
        'data_movimentacao' => now(),
    ]);

    return redirect()->back()->with('success', 'Entrada registrada com sucesso!');
    }

}
