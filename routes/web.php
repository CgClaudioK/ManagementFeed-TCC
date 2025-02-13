<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InsumoController;
use App\Http\Controllers\Admin\FormulacaoController;
use App\Http\Controllers\Admin\ProdutoController;
use App\Http\Controllers\Admin\BateladaController;
use App\Http\Controllers\Admin\MovimentacaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::post('/dashboard/processar', [DashboardController::class, 'processar'])->name('dashboard.processar');


    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        
        Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        
        Route::patch('admin/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('insumos', InsumoController::class);
    });

    // Rota para listar o histórico de movimentações
    Route::get('/admin/insumos/movimentacoes', [MovimentacaoController::class, 'historico'])
        ->name('admin.insumos.movimentacoes');

    // Rota para mostrar uma movimentação específica
    Route::get('/admin/insumos/movimentacao/{id}', [MovimentacaoController::class, 'show'])
        ->name('admin.insumos.movimentacao');

    Route::get( '/admin/formulacoes', [FormulacaoController::class, 'index'])
        ->name('admin.formulacoes.index');

    Route::get( '/admin/formulacoes/create', [FormulacaoController::class, 'create'])
        ->name('admin.formulacoes.create');

    Route::get( '/admin/formulacoes/{formulacao}/edit', [FormulacaoController::class, 'edit'])
        ->name('admin.formulacoes.edit');

    Route::put('/admin/formulacoes/{formulacao}', [FormulacaoController::class, 'update'])
        ->name('admin.formulacoes.update');

    Route::post('/admin/formulacoes/store', [FormulacaoController::class, 'formulacoes'])
        ->name('admin.formulacoes.store');

    Route::delete('/admin/formulacoes/{formulacao}', [FormulacaoController::class, 'destroy'])
        ->name('admin.formulacoes.destroy');

    Route::get('/admin/formulacoes/{id}/insumos', [FormulacaoController::class, 'getInsumos']);

    Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
        Route::resource('bateladas', BateladaController::class);
        Route::get('bateladas/create', [BateladaController::class, 'create'])->name('bateladas.create');
    });

    Route::get('admin/bateladas/exportarcsv', [BateladaController::class, 'exportarcsv'])->name('admin.bateladas.exportarcsv');
    Route::post('admin/bateladas/distribuir/{formulacao_id}', [BateladaController::class, 'distribuir'])->name('bateladas.distribuir');
    Route::get( 'admin/bateladas/relatorio', [BateladaController::class, 'relatorio'])->name('admin.bateladas.relatorio');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index'); // Listar produtos
        Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create'); // Criar produto
        Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store'); // Salvar produto
        Route::get('/produtos/{produto}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit'); // Editar produto
        Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update'); // Atualizar produto
        Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy'); // Excluir produto
    });

});
require __DIR__.'/auth.php';
