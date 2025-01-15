<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InsumoFormRequest;
use Illuminate\Http\Request;
use App\Models\Insumo;
use App\Models\Produto; // Certifique-se de importar o modelo Produto


class InsumoController extends Controller
{
    public function __construct(private Insumo $insumo)
    {
    }

    public function index()
    {
        $insumos = $this->insumo->paginate(10);
        return view('admin.insumos.index', compact('insumos'));
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
        Insumo::create($validated);

        return redirect()->route('admin.insumos.index')->with('success', 'Insumo cadastrado com sucesso!');
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
}
