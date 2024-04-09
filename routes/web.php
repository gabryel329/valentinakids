<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoProdutoController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\TamanhoController;
use App\Http\Controllers\TipoProdController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('pedido.create');
// });

Auth::routes();

Route::get('/', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/criar', [PedidoProdutoController::class, 'index'])->name('pedidos.index');
Route::middleware(['auth', 'checkUserRole:Client'])->group(function () {

    #Pedido
    Route::get('/pedidos', [PedidoController::class, 'lista']);
    Route::get('/pedidos/lista', [PedidoController::class, 'pedidos'])->name('pedidos.lista');
    Route::put('/pedidos/{id}/update', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::get('/filtro', [PedidoController::class, 'filtro']);
    Route::post('/relatorio', [PedidoController::class, 'relatorio']);

    #Produtos
    Route::get('produtos', [ProdutoController::class, 'index'])->name('produtos.index');
    Route::get('produtos/{id}', [ProdutoController::class, 'show'])->name('produtos.show');
    Route::post('produtos', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::put('produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::delete('produtos/{id}', [ProdutoController::class, 'destroy'])->name('produtos.destroy');

    #Tipos de Produtos
    Route::get('tipoProd', [TipoProdController::class, 'index'])->name('tipoProd.index');
    Route::get('tipoProd/{id}', [TipoProdController::class, 'show'])->name('tipoProd.show');
    Route::post('tipoProd', [TipoProdController::class, 'store'])->name('tipoProd.store');
    Route::put('tipoProd/{id}', [TipoProdController::class, 'update'])->name('tipoProd.update');
    Route::delete('tipoProd/{id}', [TipoProdController::class, 'destroy'])->name('tipoProd.destroy');

    #Empresa
    Route::get('Empresa', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::get('Empresa/{id}', [EmpresaController::class, 'show'])->name('empresa.show');
    Route::post('Empresa', [EmpresaController::class, 'store'])->name('empresa.store');
    Route::put('Empresa/{id}', [EmpresaController::class, 'update'])->name('empresa.update');
    Route::delete('Empresa/{id}', [EmpresaController::class, 'destroy'])->name('empresa.destroy');

    #Tamanhos de Produtos
    Route::get('tamanho', [TamanhoController::class, 'index'])->name('tamanho.index');
    Route::get('tamanho/{id}', [TamanhoController::class, 'show'])->name('tamanho.show');
    Route::post('tamanho', [TamanhoController::class, 'store'])->name('tamanho.store');
    Route::put('tamanho/{id}', [TamanhoController::class, 'update'])->name('tamanho.update');
    Route::delete('tamanho/{id}', [TamanhoController::class, 'destroy'])->name('tamanho.destroy');
});

#Processo Checkout
Route::post('/pedido/store', [PedidoController::class, 'store'])->name('pedidos.store');
Route::get('/pedido/checkout/{pedido}', [PedidoController::class, 'showCheckoutForm'])->name('pedido.checkout');
Route::post('/pedido/checkout/{pedido}', [PedidoController::class, 'processCheckout'])->name('pedido.processCheckout');
Route::delete('/pedido/{pedido}/produtos/{produto}', [PedidoController::class, 'removeProduto'])->name('pedido.removeProduto');

#Processo Checkout SEM WHATSAPP
Route::post('/pedidos/store', [PedidoProdutoController::class, 'store'])->name('pedido.store');
Route::get('/pedidos/checkout/{pedido}', [PedidoProdutoController::class, 'showCheckoutForm'])->name('pedidos.checkout');
Route::post('/pedidos/checkout/{pedido}', [PedidoProdutoController::class, 'processCheckout'])->name('pedidos.processCheckout');
Route::delete('/pedidos/{pedido}/produtos/{produto}', [PedidoProdutoController::class, 'removeProduto'])->name('pedidos.removeProduto');






