<?php

namespace App\Http\Controllers;

use App\Models\Formulacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard com as formulações.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->status !== 'ATIVO') {
            Auth::logout(); // Deslogar o usuário
            return redirect()->route('login')->with('error', 'Sua conta está inativa. Entre em contato com o administrador.');
        }
        // Carrega as formulações com os insumos e produtos relacionados
        \DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $formulacoes = Formulacao::query()
            ->join('formulacao_insumos as fi', 'formulacoes.id', '=', 'fi.formulacao_id')
            ->join('insumos as i', 'fi.insumo_id', '=', 'i.id_produto')
            ->join('produtos as p', 'fi.insumo_id', '=', 'p.id')
            ->select(
                'formulacoes.*',
                'fi.quantidade as quantidade_insumo',
                'i.id_produto',
                'i.unidade',
                'p.nome_produto'
            )
            ->get();

        // dd($formulacoes);

        // $formulacoes = Formulacao::with(['insumos' => function($query) {
        //     $query->where('insumos.id_produto', '=', \DB::raw('formulacao_insumos.insumo_id'));
        // }])->get();

        // Retorna a view com os dados
        return view('dashboard', compact('formulacoes'));
    }
}

