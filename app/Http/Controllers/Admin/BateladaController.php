<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\MovimentacaoEstoque;
use App\Models\Batelada; // Importar o modelo de Batelada
use App\Models\Estoque; // Importar o modelo de Estoque
use App\Models\Formulacao;
use Illuminate\Support\Facades\DB;

class BateladaController extends Controller
{
    public function index()
    {
        $bateladas = Batelada::paginate(10);

        $bateladaRelatorio = DB::table('bateladas')
        ->join('formulacoes', 'bateladas.formulacao_id', '=', 'formulacoes.id')
        ->select(
            'formulacoes.nome as nome_formulacao',
            DB::raw('MONTH(bateladas.data_producao) as mes'),
            DB::raw('YEAR(bateladas.data_producao) as ano'),
            DB::raw('SUM(bateladas.quantidade_produzida) as quantidade_total'),
            DB::raw('AVG(bateladas.custo_total) as custo_total'),
            DB::raw('AVG(bateladas.valor_por_kg) as valor_por_kg')
        )
        ->groupBy('formulacoes.nome', DB::raw('YEAR(bateladas.data_producao)'), DB::raw('MONTH(bateladas.data_producao)'))
        ->orderBy(DB::raw('YEAR(bateladas.data_producao)'), 'desc')
        ->orderBy(DB::raw('MONTH(bateladas.data_producao)'), 'desc')
        ->get();

        return view('admin.bateladas.index', compact('bateladas', 'bateladaRelatorio'));
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

        $formulacaoInsumos = DB::table('formulacao_insumos as fi')
        ->join('insumos as i', 'fi.insumo_id', '=', 'i.id_produto')
        ->where('fi.formulacao_id', $validated['formulacao_id'])
        ->select('i.id_produto', 'fi.insumo_id', 'i.valor_insumo_kg', 'i.id', DB::raw('SUM(i.kg_insumo_total) as quantidade'))
        ->groupBy('i.id_produto', 'fi.insumo_id', 'i.valor_insumo_kg', 'i.id')
        ->get()
        ->unique('id_produto')
        ->filter(function ($insumo) {
            return $insumo->quantidade > 0;
        });

        $quantidadeMaxima = null;

        if ($formulacaoInsumos->isEmpty()) {
            return redirect()->back()->with('error', 'Não há insumos válidos para a produção da batelada.');
        }

        foreach ($formulacaoInsumos as $insumo) {
            // Quantidade necessária de insumo para 1kg da batelada
            $quantidadeNecessariaPorKg = $insumo->quantidade; 

            // Buscando o insumo na tabela 'insumos' pelo id_produto
            $insumoDisponivel = DB::table('insumos')
                ->where('id_produto', $insumo->insumo_id)
                ->first();

            // Verificando se o insumo foi encontrado
            if ($insumoDisponivel) {
                $quantidadeDisponivel = $insumoDisponivel->kg_insumo_total; // A quantidade disponível para o insumo

                // Calculando a quantidade máxima de batelada que pode ser produzida com o insumo disponível
                $quantidadeMaximaPorInsumo = floor($quantidadeDisponivel / $quantidadeNecessariaPorKg);

                // Atualizando a quantidade máxima considerando o limite dos insumos
                if (is_null($quantidadeMaxima) || $quantidadeMaximaPorInsumo < $quantidadeMaxima) {
                    $quantidadeMaxima = $quantidadeMaximaPorInsumo;
                }
            }
        }
        // Mostrar a quantidade máxima que pode ser produzida
        session()->flash('max_batelada', $quantidadeMaxima);

        $calculos = $this->calcularCustoTotalEValorKg($formulacaoInsumos, $validated['quantidade_produzida']);

        // Verificação de estoque antes de criar a batelada
        foreach ($formulacaoInsumos as $insumo) {
            $quantidadeSaida = $insumo->quantidade * $validated['quantidade_produzida'];

            // $insumoEstoque = Insumo::where('id_produto', $insumo->pivot->insumo_id)->first();
            $insumoEstoque = DB::table('insumos as i')
                                ->join('produtos as p', 'i.id_produto', '=', 'p.id')
                                ->where('id_produto', '=',$insumo->insumo_id)
                                ->get('*');

            
            $kgInsumoTotais = $insumoEstoque->sum('kg_insumo_total');
            // Verificar se há estoque suficiente
            if ($kgInsumoTotais < $quantidadeSaida) {
                // Se não houver estoque suficiente, redirecionar de volta com a mensagem de erro
                return redirect()->back()->with('error', 'Não há insumos suficientes para a batelada. Insumo: ' . 
                   $insumoEstoque->first()->nome_produto . ' | Quantidade Disponível: ' . $kgInsumoTotais . 'kg');
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
            'tipo_movimento' => 'entrada'
        ]);

        // Atualizar o estoque dos insumos
        foreach ($formulacaoInsumos as $insumo) {
            $quantidadeSaida = $insumo->quantidade * $validated['quantidade_produzida'];

            // Atualiza o estoque do insumo
            $insumoEstoque = Insumo::where('id_produto', $insumo->insumo_id)->first();
            $insumoEstoque->kg_insumo_total -= $quantidadeSaida;

            if ($insumoEstoque && $insumoEstoque->kg_insumo_total >= $quantidadeSaida) {
                $insumoEstoque->kg_insumo_total -= $quantidadeSaida;
                $insumoEstoque->save();


            // Registra a movimentação de saída
            MovimentacaoEstoque::create([
                'insumo_id' => $insumoEstoque->id,
                'tipo' => 'saida',
                'quantidade' => $quantidadeSaida,
                'valor_unitario' => $insumoEstoque->valor_insumo_kg,
                'valor_total' => $quantidadeSaida * $insumoEstoque->valor_insumo_kg,
                'data_movimentacao' => now(),
            ]);
            } else {
                return redirect()->back()->with('error', 'Não há estoque suficiente para o insumo: ' . ($insumoEstoque->nome_produto ?? 'Desconhecido'));
            }
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
        // dd($formulacao);
        $custoTotal = 0;
        $quantidadeTotalInsumos = 0; // Variável para armazenar a soma das quantidades dos insumos
        $valorPorKg = 0;
        $quantidadeGastaInsumos = []; // Array para armazenar a quantidade gasta de cada insumo

        // Iterando sobre os insumos
        foreach ($formulacao as $insumo) {
            // Acessando a quantidade e o valor corretamente
            $quantidadeInsumo = $insumo->quantidade; // Quantidade do insumo na formulação
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

    public function distribuir(Request $request, $formulacao_id)
    {
        $validated = $request->validate([
            'quantidade_distribuida' => 'required|numeric|min:1',
        ]);

        // Pega todas as bateladas da formulação
        $bateladas = Batelada::where('formulacao_id', $formulacao_id)->pluck('id');

        // Soma a quantidade produzida de todas as bateladas dessa formulação
        $quantidadeProduzida = Batelada::where('formulacao_id', $formulacao_id)->sum('quantidade_produzida');

        // Soma todas as saídas de estoque vinculadas a essas bateladas
        $quantidadeSaida = Estoque::whereIn('batelada_id', $bateladas)
            ->where('tipo_movimento', 'saida')
            ->sum('quantidade_movimento');

        // Calcula o saldo disponível
        $quantidadeDisponivel = $quantidadeProduzida - $quantidadeSaida;

        // Verifica se há estoque suficiente para distribuir
        if ($quantidadeDisponivel < $validated['quantidade_distribuida']) {
            return redirect()->back()->with('error', 'Quantidade insuficiente no estoque.');
        }
        // $batelada = Batelada::where('formulacao_id', $formulacao_id)->first();

        $batelada = Batelada::where('formulacao_id', $formulacao_id)
        ->orderBy('data_producao', 'asc')
        ->first();

        // Registrar movimentação de saída
        Estoque::create([
            'batelada_id' => $batelada->id,
            'quantidade_movimento' => $validated['quantidade_distribuida'],
            'tipo_movimento' => 'saida',
        ]);

        return redirect()->back()->with('success', 'Distribuição realizada com sucesso.');
    }


    public function relatorio()
    {
        $bateladas = Batelada::findOrFail(41);

        return view('admin.bateladas.relatorio', compact('bateladas'));
    }

    public function exportarCsv()
    {
        $bateladas = DB::table('bateladas as b')
            ->join('formulacoes as f', 'b.formulacao_id', '=', 'f.id')
            ->join('formulacao_insumos as fi', 'f.id', '=', 'fi.formulacao_id')
            ->join('insumos as i', 'fi.insumo_id', '=', 'i.id_produto')
            ->join('produtos as p', 'i.id_produto', '=', 'p.id')
            ->select(
                'b.id as batelada_id',
                'b.formulacao_id',
                'b.quantidade_produzida',
                'b.custo_total',
                'b.valor_por_kg',
                'b.data_producao',
                DB::raw("GROUP_CONCAT(p.nome_produto ORDER BY p.nome_produto ASC SEPARATOR ', ') as produtos"),
                DB::raw("GROUP_CONCAT(fi.quantidade ORDER BY p.nome_produto ASC SEPARATOR ', ') as quantidades")
            )
            ->groupBy('b.id', 'b.formulacao_id', 'b.quantidade_produzida', 'b.custo_total', 'b.valor_por_kg', 'b.data_producao')
            ->get();

        // Criar o arquivo CSV
        $fileName = 'relatorio_bateladas.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID Batelada', 'Formulação ID', 'Quantidade Produzida', 'Custo Total', 'Valor por KG', 'Data de Produção', 'Produtos', 'Quantidades']);

        foreach ($bateladas as $batelada) {
            fputcsv($handle, [
                $batelada->batelada_id,
                $batelada->formulacao_id,
                number_format($batelada->quantidade_produzida, 2, ',', '.'),
                number_format($batelada->custo_total, 2, ',', '.'),
                number_format($batelada->valor_por_kg, 2, ',', '.'),
                date('d/m/Y', strtotime($batelada->data_producao)),
                $batelada->produtos,
                $batelada->quantidades
            ]);
        }

        fclose($handle);
        
        return response()->stream(
            function () use ($handle) {
                fclose($handle);
            }, 200, $headers
        );
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
