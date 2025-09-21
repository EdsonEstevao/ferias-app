<?php

use App\Http\Controllers\FeriasController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServidorController;
use App\Http\Controllers\UserController;
use App\Livewire\FeriasPainel;
use App\Livewire\GestorFeriasPainel;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['permission:visualizar ferias'])->group(function () {
    Route::get('/ferias', [FeriasController::class, 'index'])->name('ferias.index');
});

Route::middleware(['role:admin|gestor'])->group(function () {
    Route::prefix('/ferias')->group(function () {
        Route::get('/create', [FeriasController::class, 'create'])->name('ferias.create');
        Route::post('/', [FeriasController::class, 'store'])->name('ferias.store');
        Route::post('/lancar', [FeriasController::class, 'salvarTodos'])->name('ferias.lancar');
        Route::get('/interromper-ferias', [FeriasController::class, 'interromperFerias'])->name('ferias.interromper.periodo');
        Route::post('/interromper', [FeriasController::class, 'interromper'])->name('ferias.interromper');
        Route::post('/ferias/remarcar', [FeriasController::class, 'remarcar'])->name('ferias.remarcar');


    });

});


Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::post('/admin/roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('admin.roles.syncPermissions');
});
Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}/roles', [UserController::class, 'updateRoles'])->name('admin.users.updateRoles');
});

// Gestor
Route::middleware(['role:gestor|admin'])->group(function () {
    Route::get('/servidores', [ServidorController::class, 'index'])->name('servidores.index');

});

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

require __DIR__.'/auth.php';