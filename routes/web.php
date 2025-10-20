<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeriasController;
use App\Http\Controllers\FeriasImportController;
use App\Http\Controllers\FeriasPeriodosController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SecretariaController;
use App\Http\Controllers\ServidorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VinculoController;
use App\Http\Controllers\VinculoFuncionalController;
use App\Livewire\FeriasPainel;
use App\Livewire\GestorFeriasPainel;
use App\Models\Cargo;
use App\Models\CargoSecretariaSimbologia;
use App\Models\Secretaria;
use App\Models\Simbologia;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/api/dashboard/dados', [DashboardController::class, 'dadosDashboard']);
Route::get('/api/dashboard/estatisticas', [DashboardController::class, 'estatisticasDetalhadas']);


// Route::middleware(['permission:visualizar ferias'])->group(function () {
//     Route::get('/ferias', [FeriasController::class, 'index'])->name('ferias.index');
// });

Route::middleware(['role:admin|gestor'])->group(function () {
    Route::prefix('/ferias')->group(function () {
        Route::get('/', [FeriasController::class, 'index'])->name('ferias.index');

        Route::get('/create/{servidor}', [FeriasController::class, 'create'])->name('ferias.create');
        Route::get('/{id}/show', [FeriasController::class, 'show'])->name('ferias.show');
        Route::get('/{id}/edit', [FeriasController::class, 'edit'])->name('ferias.edit');
        Route::put('/{id}', [FeriasController::class, 'update'])->name('ferias.update');

        Route::post('/store', [FeriasController::class, 'store'])->name('ferias.store');
        Route::post('/lancar', [FeriasController::class, 'salvarTodos'])->name('ferias.lancar');
        Route::get('/interromper-ferias', [FeriasController::class, 'interromperFerias'])->name('ferias.interromper.periodo');
        Route::post('/interromper', [FeriasController::class, 'interromper'])->name('ferias.interromper');
        Route::post('/remarcar', [FeriasController::class, 'remarcar'])->name('ferias.remarcar');
        Route::post('/fracionar', [FeriasController::class, 'fracionar'])->name('ferias.fracionar');
        Route::get('/{servidor}/pdf', [FeriasController::class, 'gerarPdf'])->name('ferias.pdf');
        Route::get('/api/ferias', [FeriasController::class, 'filtrar']);

        // Importar Arquivo Csv
        Route::get('/import', [FeriasImportController::class, 'index'])->name('ferias.import');
        Route::post('/import', [FeriasImportController::class, 'importCsv'])->name('ferias.import.csv');
    });

});

// Servidores
Route::middleware(['role:admin|gestor'])->group(function () {

    Route::prefix('/servidores')->group(function () {
        Route::get('/', [ServidorController::class, 'index'])->name('servidores.index');
        Route::get('/{servidor}/edit', [ServidorController::class, 'edit'])->name('servidores.edit');
        Route::put('/{servidor}', [ServidorController::class, 'update'])->name('servidores.update');
        Route::get('/create', [ServidorController::class, 'create'])->name('servidores.create');
        Route::post('/', [ServidorController::class, 'store'])->name('servidores.store');
        Route::get('/{servidor}', [ServidorController::class, 'show'])->name('servidores.show');
        Route::delete('/{servidor}', [ServidorController::class, 'destroy'])->name('servidores.destroy');
    });

});


// Movimentações Api
Route::get('/api/movimentacoes', [VinculoFuncionalController::class, 'index']);
Route::post('/api/movimentacoes', [VinculoFuncionalController::class, 'store']);
Route::get('/relatorio/movimentacoes', [VinculoFuncionalController::class, 'pdf']);
Route::get('/exportar/movimentacoes', [VinculoFuncionalController::class, 'excel']);



//Secretarias Api
Route::get('/vinculo-cargos-secretarias', [VinculoController::class, 'index'])->name('vinculo.cargos.secretarias');
Route::get('/vinculos', [VinculoController::class, 'indexJson'])->name('vinculos.json');
Route::post('/api/vinculos', [VinculoController::class, 'store']);



Route::get('/secretarias', [SecretariaController::class, 'index'])->name('secretarias.index');
Route::get('/secretarias/create', [SecretariaController::class, 'create'])->name('secretarias.create');
Route::post('/secretarias', [SecretariaController::class, 'store'])->name('secretarias.store');
Route::get('/secretarias/{secretaria}/edit', [SecretariaController::class, 'edit'])->name('secretarias.edit');
Route::put('/secretarias/{secretaria}', [SecretariaController::class, 'update'])->name('secretarias.update');
Route::delete('/secretarias/{secretaria}', [SecretariaController::class, 'destroy'])->name('secretarias.destroy');


// Cargos por Secretaria
Route::get('api/cargos/{secretaria}', [CargoController::class, 'getCargosBySecretaria'])->name('api.cargos.by.secretaria');


// Route::put('/secretarias/{id}', [SecretariaController::class, 'update'])->name('secretarias.update');
// Route::delete('/secretarias/{id}', [SecretariaController::class, 'destroy'])->name('secretarias.destroy');




// Relatorios de ferias do sevidores ativos
Route::get('/relatorios/ferias-ativas', [RelatorioController::class, 'feriasAtivasPdf'])->name('relatorio.ferias.ativas.pdf');
Route::get('/verificar-ferias', [RelatorioController::class, 'verificarFerias']);


Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::post('/admin/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('admin.roles.syncPermissions');

    Route::group(['prefix' => '/admin'], function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('admin.users.updateRoles');

    });
});


// Gestor
// Route::middleware(['role:gestor|admin'])->group(function () {
//     Route::get('/servidores', [ServidorController::class, 'index'])->name('servidores.index');

// });

// Route::get('/gestor/ferias/{servidor}', FeriasPainel::class)
//     ->middleware(['role:gestor|admin'])
//     ->name('gestor.ferias.painel');
Route::get('/gestor/ferias/{servidorId}', FeriasPainel::class)
    ->middleware(['role:gestor|admin'])
    ->name('gestor.ferias.painel');

// Route::get('/gestor/servidores/{servidor}/ferias', GestorFeriasPainel::class)
// ->middleware(['role:gestor|admin'])
// ->name('gestor.ferias.painel');



Route::middleware(['role:gestor|admin'])->group(function (){
    Route::get('/servidores', [ServidorController::class, 'index'])->name('servidores.index');
    Route::get('/servidores/{servidor}/edit', [ServidorController::class, 'edit'])->name('servidores.edit');
    Route::put('/servidores/{servidor}', [ServidorController::class, 'update'])->name('servidores.update');
    Route::get('/servidores/create', [ServidorController::class, 'create'])->name('servidores.create');
    Route::post('/servidores', [ServidorController::class, 'store'])->name('servidores.store');
    Route::get('/servidores/{servidor}', [ServidorController::class, 'show'])->name('servidores.show');
    Route::delete('/servidores/{servidor}', [ServidorController::class, 'destroy'])->name('servidores.destroy');

    // Route::get('gestor/servidores/{servidor}/ferias', GestorFeriasPainel::class)->name('gestor.ferias.painel');
});
// get('/servidores/{servidor}', function () {
//     return view('servidores.perfil', compact('servidor'));
// })->middleware(['auth'])->name('servidores.perfil');





Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rotas para Férias
// Route::get('/ferias', [FeriasController::class, 'index']);
// Route::delete('/ferias/{id}', [FeriasController::class, 'destroy']);

// Rotas para Períodos de Férias
Route::get('/api/periodos-ferias', [FeriasPeriodosController::class, 'index']);
Route::post('/api/periodos-ferias', [FeriasPeriodosController::class, 'store']);
Route::put('/api/periodos-ferias/{id}', [FeriasPeriodosController::class, 'update']);
Route::delete('/api/periodos-ferias/{id}', [FeriasPeriodosController::class, 'destroy']);

// routes/api.php
// Route::put('/periodos-ferias/{id}', [FeriasPeriodosController::class, 'update']);
// Route::delete('/periodos-ferias/{id}', [FeriasPeriodosController::class, 'destroy']);
Route::delete('/api/ferias/{id}', [FeriasController::class, 'destroy']);

require __DIR__.'/auth.php';