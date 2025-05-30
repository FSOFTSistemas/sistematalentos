<?php

use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PlanoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\MembroController;
use App\Http\Controllers\DizimoController;
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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

// Rotas de autenticação
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Usuario
    Route::resource('usuarios', UserController::class);

    // Caixa
    Route::resource('caixa', CaixaController::class);

    // Membros
    Route::resource('membros', MembroController::class);

    // Dízimos
    Route::resource('dizimos', DizimoController::class);

    // Despesas
    Route::resource('despesas', DespesaController::class);

    // Relatórios

    // // Usuários (com middleware de permissão)
    // Route::middleware(['role:admin'])->group(function () {
    //     Route::resource('usuarios', UserController::class);
    // });
});

 // Rotas para relatórios
Route::middleware(['auth'])->prefix('relatorios')->name('relatorios.')->group(function () {
    Route::get('/mensal', [RelatorioController::class, 'mensal'])->name('mensal');
    Route::get('/mensal/pdf', [RelatorioController::class, 'mensalPdf'])->name('mensal.pdf');
    Route::get('/mensal/excel', [RelatorioController::class, 'mensalExcel'])->name('mensal.excel');
    
    Route::get('/dizimistas', [RelatorioController::class, 'dizimistas'])->name('dizimistas');
    Route::get('/dizimistas/pdf', [RelatorioController::class, 'dizimistasPdf'])->name('dizimistas.pdf');
    Route::get('/dizimistas/excel', [RelatorioController::class, 'dizimistasExcel'])->name('dizimistas.excel');
    
    Route::get('/balanco', [RelatorioController::class, 'balanco'])->name('balanco');
    Route::get('/balanco/pdf', [RelatorioController::class, 'balancoPdf'])->name('balanco.pdf');
    Route::get('/balanco/excel', [RelatorioController::class, 'balancoExcel'])->name('balanco.excel');

    Route::get('/graficos', [RelatorioController::class, 'graficos'])->name('graficos');
});

// Rotas para master
Route::middleware(['auth', 'master'])->group(function () {
    Route::resource('planos', PlanoController::class);
    Route::resource('empresas', EmpresaController::class);
    Route::post('empresas/{empresa}/usuarios', [EmpresaController::class, 'addUser'])->name('empresas.add-user');
    Route::post('empresas/{empresa}/renovar', [EmpresaController::class, 'renovarPlano'])->name('empresas.renovar-plano');
});

Auth::routes();

// Rotas para página de vendas e checkout
Route::get('/vendas', [App\Http\Controllers\VendasController::class, 'index'])->name('vendas.index');
Route::post('/vendas/checkout', [App\Http\Controllers\VendasController::class, 'checkout'])->name('vendas.checkout');
Route::post('/vendas/webhook', [App\Http\Controllers\VendasController::class, 'webhook'])->name('vendas.webhook');

// Rota para a landing page
Route::get('/landing', function () {
    return view('landing.index');
})->name('landing');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
