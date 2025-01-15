<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InsumoController;
use App\Http\Controllers\Admin\FormulacaoController;
use App\Http\Controllers\Admin\ProdutoController;
use App\Http\Controllers\Admin\BateladaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
}); 

Route::get('/test-email', [UserController::class, 'sendTestEmail']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('insumos', InsumoController::class);
});

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
});

Route::get('admin.bateladas.create', [BateladaController::class, 'create'])->name('bateladas.create');


Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/produtos', [ProdutoController::class, 'index'])->name('produtos.index'); // Listar produtos
    Route::get('/produtos/create', [ProdutoController::class, 'create'])->name('produtos.create'); // Criar produto
    Route::post('/produtos', [ProdutoController::class, 'store'])->name('produtos.store'); // Salvar produto
    Route::get('/produtos/{produto}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit'); // Editar produto
    Route::put('/produtos/{produto}', [ProdutoController::class, 'update'])->name('produtos.update'); // Atualizar produto
    Route::delete('/produtos/{produto}', [ProdutoController::class, 'destroy'])->name('produtos.destroy'); // Excluir produto
});

// Gerenciamento da Batelada


// Route::get('admin/bateladas', [BateladaController::class, 'index'])->name('bateladas.index');
// Route::get('admin/bateladas/{id}', [BateladaController::class, 'show'])->name('bateladas.show');
// Route::resource('/admin/bateladas', BateladaController::class);






require __DIR__.'/auth.php';
