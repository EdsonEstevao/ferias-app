<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExoneracaoController;
use App\Http\Controllers\FeriasController;
use App\Http\Controllers\FeriasImportController;
use App\Http\Controllers\FeriasPeriodosController;
use App\Http\Controllers\NomeacaoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SecretariaController;
use App\Http\Controllers\ServidorController;
use App\Http\Controllers\ServidorImportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VinculoController;
use App\Http\Controllers\VinculoFuncionalController;
use App\Livewire\FeriasPainel;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth')->get('/', [AuthenticatedSessionController::class, 'create'])->name('login');

// Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

// APIs para o dashboard
Route::get('/dashboard/calendario', [DashboardController::class, 'calendarioData'])->name('dashboard.calendario');
Route::get('/dashboard/dados', [DashboardController::class, 'dadosDashboard'])->name('dashboard.dados');
Route::get('/dashboard/estatisticas', [DashboardController::class, 'estatisticasDetalhadas'])->name('dashboard.estatisticas');
Route::get('/dashboard/servidor/{servidorId}/periodos', [DashboardController::class, 'periodosPorServidor'])->name('dashboard.periodos-servidor');



Route::middleware('auth')->group(function () {
    Route::get('/online-users-count', function () {

        try {
            return response()->json([
                'status' => 'success',
                'count' => getOnlineUsersCount(),
            ]);
        }
        catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    });
});

// Férias
Route::middleware(['role:admin|gestor|super admin'])->group(function () {
    Route::prefix('/ferias')->group(function () {
        Route::get('/', [FeriasController::class, 'index'])->name('ferias.index');

        Route::get('/create/{servidor}', [FeriasController::class, 'create'])->name('ferias.create');
        Route::get('/periodo/{id}/detalhes', [FeriasController::class, 'detalhes'])->name('ferias.detalhes');
        Route::get('/{id}/show', [FeriasController::class, 'show'])->name('ferias.show');
        Route::get('/{id}/edit', [FeriasController::class, 'edit'])->name('ferias.edit');
        Route::put('/{id}', [FeriasController::class, 'update'])->name('ferias.update');

        Route::post('/store', [FeriasController::class, 'store'])->name('ferias.store');
        Route::post('/lancar', [FeriasController::class, 'salvarTodos'])->name('ferias.lancar');
        Route::get('/interromper-ferias', [FeriasController::class, 'interromperFerias'])->name('ferias.interromper.periodo');
        Route::post('/interromper', [FeriasController::class, 'interromper'])->name('ferias.interromper');
        Route::post('/remarcar', [FeriasController::class, 'remarcar'])->name('ferias.remarcar');

        Route::post('/remarcar-multiplos', [FeriasController::class, 'remarcarMultiplosPeriodos'])
        ->name('ferias.remarcar.multiplus');


        Route::post('/fracionar', [FeriasController::class, 'fracionar'])->name('ferias.fracionar');
        Route::get('/{servidor}/pdf', [FeriasController::class, 'gerarPdf'])->name('ferias.pdf');
        Route::get('/api/ferias', [FeriasController::class, 'filtrar']);

        // Importar Arquivo Csv
        Route::get('/import', [FeriasImportController::class, 'index'])->name('ferias.import');
        Route::post('/import', [FeriasImportController::class, 'importCsv'])->name('ferias.import.csv');



        Route::get('/filtro', [FeriasController::class, 'filtro'])->name('ferias.filtro');
        Route::get('/filtro-pdf', [FeriasController::class, 'filtroPdf'])->name('ferias.filtro.pdf');
        Route::get('/filtro/excel', [FeriasController::class, 'filtroExcel'])->name('ferias.filtro.excel');
        // Api para o filtro
        Route::get('/filtro/dados', [FeriasController::class, 'filtroDados'])->name('ferias.filtro.dados');
    });

});


 // routes/web.php
Route::get('/servidores-nomeados', [NomeacaoController::class, 'index'])->name('servidores.nomeados.index');

// Servidores - Todas as rotas organizadas
Route::middleware(['role:admin|gestor|super admin'])->group(function () {

    Route::prefix('servidores')->name('servidores.')->group(function () {



        // Nova rota para visualização por departamento
        Route::get('/por-departamento', [ServidorController::class, 'porDepartamento'])
             ->name('por-departamento');

        // Rotas de nomeação
        Route::get('/{servidor}/nomeacao/create', [NomeacaoController::class, 'create'])->name('nomeacao.create');
        Route::post('/{servidor}/nomeacao', [NomeacaoController::class, 'store'])->name('nomeacao.store');


        // Rotas de exoneração
        Route::get('/exoneracao', [ExoneracaoController::class, 'index'])->name('exoneracao.index');
        Route::get('/exoneracao/create/{servidor}', [ExoneracaoController::class, 'create'])->name('exoneracao.create');
        Route::post('/exoneracao/{servidor}', [ExoneracaoController::class, 'store'])->name('exoneracao.store');
        Route::put('/exoneracao/{vinculo}/restaurar', [ExoneracaoController::class, 'restaurar'])->name('exoneracao.restaurar');



        // CRUD
        Route::get('/', [ServidorController::class, 'index'])->name('index');
        Route::get('/create', [ServidorController::class, 'create'])->name('create');
        Route::post('/', [ServidorController::class, 'storeServidor'])->name('store');
        // Route::post('/', [ServidorController::class, 'store'])->name('store');
        Route::get('/{servidor}', [ServidorController::class, 'show'])->name('show');
        Route::get('/{servidor}/edit', [ServidorController::class, 'edit'])->name('edit');
        Route::put('/{servidor}', [ServidorController::class, 'update'])->name('update');
        Route::delete('/{servidor}', [ServidorController::class, 'destroy'])->name('destroy');





        /**
         * inicio
         */

        // Route::middleware(['role:gestor|admin|super admin'])->group(function (){
            // Route::get('/servidores', [ServidorController::class, 'index'])->name('servidores.index');
            // Route::get('/servidores/{servidor}/edit', [ServidorController::class, 'edit'])->name('servidores.edit');
            // Route::put('/servidores/{servidor}', [ServidorController::class, 'update'])->name('servidores.update');
            // Route::get('/servidores/create', [ServidorController::class, 'create'])->name('servidores.create');

            // Route::get('/servidores/{servidor}', [ServidorController::class, 'show'])->name('servidores.show');
            // Route::delete('/servidores/{servidor}', [ServidorController::class, 'destroy'])->name('servidores.destroy');
        // });

        /**
         * Fim
         */




        // Importação
        Route::get('/importar/interface', [ServidorImportController::class, 'showImportForm'])
             ->name('import.form');
        Route::post('/importar/preview', [ServidorImportController::class, 'preview'])
             ->name('import.preview');
        Route::post('/importar/processar', [ServidorImportController::class, 'process'])
             ->name('import.process');
        Route::get('/importar/template', [ServidorImportController::class, 'downloadJsonTemplate'])
             ->name('import.template.json');


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
Route::get('/api/cargos/{secretaria}', [CargoController::class, 'getCargosBySecretaria'])->name('api.cargos.by.secretaria');


// Relatorios de ferias do sevidores ativos
Route::get('/relatorios/ferias-ativas', [RelatorioController::class, 'feriasAtivasPdf'])->name('relatorio.ferias.ativas.pdf');
Route::get('/verificar-ferias', [RelatorioController::class, 'verificarFerias']);


Route::middleware(['role:admin|super admin'])->group(function () {
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



Route::get('/gestor/ferias/{servidorId}', FeriasPainel::class)
    ->middleware(['role:gestor|admin|super admin'])
    ->name('gestor.ferias.painel');






Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rotas para Períodos de Férias
/*/api/periodos-ferias/${periodoId}/usufruir*/

Route::get('/api/periodos-ferias', [FeriasPeriodosController::class, 'index']);
Route::post('/api/periodos-ferias', [FeriasPeriodosController::class, 'store']);
Route::post('/api/periodos-ferias/{id}/usufruir', [FeriasPeriodosController::class, 'marcarComoUsufruido']);
Route::post('/api/periodos-ferias/{id}/desusufruir', [FeriasPeriodosController::class, 'desmarcarUsufruto']);
Route::put('/api/periodos-ferias/{id}', [FeriasPeriodosController::class, 'update']);


Route::prefix('periodos-ferias')->group(function () {
    Route::put('/{periodo}', [FeriasPeriodosController::class, 'update']);
    Route::delete('/{periodo}', [FeriasPeriodosController::class, 'destroy']);
});




// routes/api.php
Route::delete('/api/ferias/{id}', [FeriasController::class, 'destroy']);



// Rotas para importação de férias via JSON
Route::middleware(['auth', 'role:super admin'])->group(function () {

    Route::prefix('ferias-import')->name('ferias-import.')->group(function () {
        Route::get('/', [FeriasImportController::class, 'indexJson'])->name('index');
        Route::get('/create', [FeriasImportController::class, 'createJson'])->name('create');
        Route::post('/', [FeriasImportController::class, 'storeJson'])->name('store');
        Route::get('/template', [FeriasImportController::class, 'template'])->name('template');
    });
});




Route::middleware(['auth', 'role:super admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
    Route::get('/audit/{audit}', [AuditController::class, 'show'])->name('audit.show');
});



require __DIR__.'/auth.php';
