<?php

namespace App\Http\Controllers;

use App\Models\Formulacao;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard com as formulações.
     */
    public function index()
    {
        // Carrega as formulações com os insumos e produtos relacionados
        $formulacoes = Formulacao::with('insumos.produto')->get();

        // Retorna a view com os dados
        return view('dashboard', compact('formulacoes'));
    }
}

