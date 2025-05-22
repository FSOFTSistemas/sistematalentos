<?php

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

    // Caixa
    Route::resource('caixa', CaixaController::class);

    // Membros
    Route::resource('membros', MembroController::class);

    // Dízimos
    Route::resource('dizimos', DizimoController::class);

    // Despesas
    Route::resource('despesas', DespesaController::class);

    // Relatórios
    Route::get('/relatorios/mensal', [RelatorioController::class, 'mensal'])->name('relatorios.mensal');
    Route::get('/relatorios/dizimistas', [RelatorioController::class, 'dizimistas'])->name('relatorios.dizimistas');
    Route::get('/relatorios/balanco', [RelatorioController::class, 'balanco'])->name('relatorios.balanco');
    
    // Usuários (com middleware de permissão)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
    });
});

// Rotas para master
Route::middleware(['auth', 'master'])->group(function () {
    Route::resource('planos', PlanoController::class);
    Route::resource('empresas', EmpresaController::class);
    Route::post('empresas/{empresa}/usuarios', [EmpresaController::class, 'addUser'])->name('empresas.add-user');
    Route::post('empresas/{empresa}/renovar', [EmpresaController::class, 'renovarPlano'])->name('empresas.renovar-plano');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
