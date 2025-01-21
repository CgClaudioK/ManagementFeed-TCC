<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\MovimentacaoEstoque;
use App\Models\Batelada; // Importar o modelo de Batelada
use App\Models\Estoque; // Importar o modelo de Estoque
use App\Models\Distribuicao; // Importar o modelo de Distribuicao
use App\Models\Formulacao; // Importar o modelo de Distribuicao

class BateladaController extends Controller
{
    public function index()
    {
        $bateladas = Batelada::paginate(10);
        return view('admin.bateladas.index', compact('bateladas'));
    }

    public function create()
    {
        $formulacoes = Formulacao::all();
        return view('admin.bateladas.create', compact('formulacoes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'formulacao_id' => 'required|exists:formulacoes,id',
            'quantidade_produzida' => 'required|numeric|min:1',
        ]);

        $formulacao = Formulacao::with('insumos')->findOrFail($validated['formulacao_id']);

        // Cálculo do máximo de batelada que pode ser produzida com os insumos disponíveis
        $quantidadeMaxima = null;
        foreach ($formulacao->insumos as $insumo) {
            $quantidadeNecessariaPorKg = $insumo->pivot->quantidade; // Quantidade do insumo necessária para 1kg da batelada
            $quantidadeDisponivel = $insumo->kg_insumo_total;

            $quantidadeMaximaPorInsumo = floor($quantidadeDisponivel / $quantidadeNecessariaPorKg); // Quanto podemos produzir com o insumo disponível

            if (is_null($quantidadeMaxima) || $quantidadeMaximaPorInsumo < $quantidadeMaxima) {
                $quantidadeMaxima = $quantidadeMaximaPorInsumo; // O limite será o menor valor entre os insumos
            }
        }

        // Mostrar a quantidade máxima que pode ser produzida
        session()->flash('max_batelada', $quantidadeMaxima);

        // Chamar o método de cálculo
        $calculos = $this->calcularCustoTotalEValorKg($formulacao, $validated['quantidade_produzida']);

        // Verificação de estoque antes de criar a batelada
        foreach ($formulacao->insumos as $insumo) {
            $quantidadeSaida = $insumo->pivot->quantidade * $validated['quantidade_produzida'];

            // Verificar se há estoque suficiente
            if ($insumo->kg_insumo_total < $quantidadeSaida) {
                // Se não houver estoque suficiente, redirecionar de volta com a mensagem de erro
                return redirect()->back()->with('error', 'Não há insumos suficientes para a batelada. Insumo: ' . 
                    $insumo->produto->nome_produto . ' | Quantidade Disponível: ' . $insumo->kg_insumo_total . 'kg');
            }
        }

        // Criar a batelada
        $batelada = Batelada::create([
            'formulacao_id' => $validated['formulacao_id'],
            'quantidade_produzida' => $validated['quantidade_produzida'],
            'custo_total' => $calculos['custo_total'],
            'valor_por_kg' => $calculos['valor_por_kg'],
            'data_producao' => now(),
        ]);

        // Criar o estoque inicial
        Estoque::create([
            'batelada_id' => $batelada->id,
            'quantidade_movimento' => $validated['quantidade_produzida'],
            'tipo_movimento' => 'ENTRADA'
        ]);

        // Atualizar o estoque dos insumos
        foreach ($formulacao->insumos as $insumo) {
            $quantidadeSaida = $insumo->pivot->quantidade * $validated['quantidade_produzida'];

            // Atualiza o estoque do insumo
            $insumo->kg_insumo_total -= $quantidadeSaida;
            $insumo->save();

            // Registra a movimentação de saída
            MovimentacaoEstoque::create([
                'insumo_id' => $insumo->id,
                'tipo' => 'saida',
                'quantidade' => $quantidadeSaida,
                'valor_unitario' => $insumo->valor_insumo_kg,
                'valor_total' => $quantidadeSaida * $insumo->valor_insumo_kg,
                'data_movimentacao' => now(),
            ]);
        }

        // Sucesso
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
    ]);

    $formulacao = Formulacao::with('insumos')->findOrFail($validated['formulacao_id']);

    // Calcula o custo total e valor por kg
    $calculos = $this->calcularCustoTotalEValorKg($formulacao, $validated['quantidade_produzida']);

    $batelada = Batelada::findOrFail($id);
    $batelada->update([
        'formulacao_id' => $validated['formulacao_id'],
        'quantidade_produzida' => $validated['quantidade_produzida'],
        'custo_total' => $calculos['custo_total'],
        'valor_por_kg' => $calculos['valor_por_kg'],
    ]);

    return redirect()->route('admin.bateladas.index')->with('success', 'Batelada atualizada com sucesso!');
}

    public function calcularCustoTotalEValorKg($formulacao, float $quantidadeProduzida)
    {
        $custoTotal = 0;
        $quantidadeTotalInsumos = 0; // Variável para armazenar a soma das quantidades dos insumos
        $valorPorKg = 0;
        $quantidadeGastaInsumos = []; // Array para armazenar a quantidade gasta de cada insumo

        // Iterando sobre os insumos
        foreach ($formulacao->insumos as $insumo) {
            // Acessando a quantidade e o valor corretamente
            $quantidadeInsumo = $insumo->pivot->quantidade; // Quantidade do insumo na formulação
            $valorInsumoKg = $insumo->valor_insumo_kg; // Valor por kg do insumo

            // Somando a quantidade total dos insumos
            $quantidadeTotalInsumos += $quantidadeInsumo;

            // Armazenando a quantidade gasta de cada insumo
            $quantidadeGastaInsumos[$insumo->id] = $quantidadeInsumo;

            

            // Somando o custo total
            $valorPorKg += $quantidadeInsumo * $valorInsumoKg;
            $custoTotal +=  $valorPorKg * $quantidadeProduzida;
        }

        // Calcula o valor por kg (custo total dividido pela quantidade produzida)
        // $valorPorKg = $quantidadeProduzida > 0 ? $custoTotal / $quantidadeProduzida : 0;

        return [
            'custo_total' => $custoTotal,
            'valor_por_kg' => $valorPorKg,
            'quantidade_gasta_insumos' => $quantidadeGastaInsumos, // Retorna a quantidade gasta de cada insumo
        ];
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
