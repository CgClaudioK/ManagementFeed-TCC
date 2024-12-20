<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\InsumoController;
use App\Http\Controllers\Admin\ReceitaController;
use App\Http\Controllers\Admin\ProdutoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::resource('insumos', InsumoController::class);


// Route::get( '/admin/insumos', [InsumoController::class, 'index'])
//     ->name('admin.insumos.index');

// Route::get( '/admin/insumos/create', [InsumoController::class, 'create'])
//     ->name('admin.insumos.create');

// Route::get( '/admin/insumos/{insumo}/edit', [InsumoController::class, 'edit'])
//     ->name('admin.insumos.edit');

// Route::post('/admin/insumos/store', [InsumoController::class, 'insumos'])
//     ->name('admin.insumos.store');

// Route::post('/admin/insumos/{insumo}/update', [InsumoController::class, 'update'])
//     ->name('admin.insumos.update');

// Route::get('/admin/insumos/{insumo}/destroy', [InsumoController::class, 'destroy'])
//     ->name('admin.insumos.destroy');

});

// Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
//     Route::resource('receitas', ReceitaController::class);
// });

Route::get( '/admin/receitas', [ReceitaController::class, 'index'])
    ->name('admin.receitas.index');

Route::get( '/admin/receitas/create', [ReceitaController::class, 'create'])
    ->name('admin.receitas.create');

Route::get( '/admin/receitas/{insumo}/edit', [ReceitaController::class, 'edit'])
    ->name('admin.receitas.edit');

Route::post('/admin/receitas/store', [ReceitaController::class, 'receitas'])
    ->name('admin.receitas.store');

Route::get('/admin/produtos/create', [InsumoController::class, 'create'])->name('admin.produtos.create');

Route::get('/admin/produtos/create', [ProdutoController::class, 'create'])->name('admin.produtos.create');
Route::post('/admin/produtos/store', [ProdutoController::class, 'store'])->name('admin.produtos.store');
Route::delete('produtos/{produto}', [ProdutoController::class, 'destroy'])->name('admin.produtos.destroy');




require __DIR__.'/auth.php';
