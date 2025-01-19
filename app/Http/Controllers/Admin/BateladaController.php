<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Batelada; // Importar o modelo de Batelada
use App\Models\Estoque; // Importar o modelo de Estoque
use App\Models\Distribuicao; // Importar o modelo de Distribuicao
use App\Models\Formulacao; // Importar o modelo de Distribuicao

class BateladaController extends Controller
{
    public function index()
    {
        $bateladas = Batelada::with('formulacao')->get();
        return view('admin.bateladas.index', compact('bateladas'));
    }

    public function create()
    {
        $formulacoes = Formulacao::all();
        // $formulacoes = Formulacao::with('insumos.produto')->get();
        return view('admin.bateladas.create', compact('formulacoes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'formulacao_id' => 'required|exists:formulacoes,id',
            'quantidade_produzida' => 'required|numeric|min:1',
        ]);
    
        $formulacao = Formulacao::with('insumos')->findOrFail($validated['formulacao_id']);
    
        // Calcula o custo total
        $custo_total = $formulacao->insumos->reduce(function ($total, $insumo) {
            return $total + $insumo->pivot->quantidade * $insumo->valor_insumo_kg;
        }, 0);
    
        // Cria a batelada
        $batelada = Batelada::create([
            'formulacao_id' => $validated['formulacao_id'],
            'quantidade_produzida' => $validated['quantidade_produzida'],
            'custo_total' => $custo_total,
            'valor_por_kg' => $custo_total / $validated['quantidade_produzida'],
            'data_producao' => now(),
        ]);
    
        // Cria o estoque inicial
        Estoque::create([
            'batelada_id' => $batelada->id,
            'quantidade_disponivel' => $validated['quantidade_produzida'],
        ]);
    
        return redirect()->route('admin.bateladas.index')->with('success', 'Batelada criada com sucesso!');
    }
    

    public function show($id)
    {
        $batelada = Batelada::with(['formulacao.insumos.produto', 'distribuicoes'])->findOrFail($id);

        return view('admin.bateladas.show', compact('batelada'));
    }

    public function edit($id)
    {
        $batelada = Batelada::findOrFail($id);
        $formulacoes = Formulacao::all();
        return view('admin.bateladas.edit', compact('batelada', 'formulacoes'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'formulacao_id' => 'required|exists:formulacoes,id',
            'quantidade_produzida' => 'required|numeric|min:1',
            'custo_total' => 'required|numeric|min:0',
        ]);

        $batelada = Batelada::findOrFail($id);
        $batelada->update([
            'formulacao_id' => $validated['formulacao_id'],
            'quantidade_produzida' => $validated['quantidade_produzida'],
            'custo_total' => $validated['custo_total'],
            'valor_por_kg' => $validated['custo_total'] / $validated['quantidade_produzida'],
        ]);

        // Atualiza o estoque
        $batelada->estoque->update([
            'quantidade_disponivel' => $validated['quantidade_produzida'], // Recalcula o estoque com base na nova quantidade
        ]);

        return redirect()->route('admin.bateladas.index')->with('success', 'Batelada atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $batelada = Batelada::findOrFail($id);
        $batelada->estoque()->delete(); // Remove o estoque associado
        $batelada->distribuicoes()->delete(); // Remove as distribuições associadas
        $batelada->delete();

        return redirect()->route('admin.bateladas.index')->with('success', 'Batelada excluída com sucesso!');
    }
    

}
