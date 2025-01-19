<?php

namespace App\Http\Controllers;

use App\Models\Formulacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        $formulacoes = Formulacao::with('insumos.produto')->get();

        // Retorna a view com os dados
        return view('dashboard', compact('formulacoes'));
    }
}

