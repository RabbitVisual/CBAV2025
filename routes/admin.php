<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    CargoController,
    ChatController,
    ConselhoController,
    DashboardController as AdminDashboardController,
    DepartmentController,
    DevocionalController,
    EBD\TurmaController as EbdTurmaController,
    EBD\GrupoController as EbdGrupoController,
    EventoController,
    EventoInscricaoController,
    FinanceController,
    IntercessorController,
    MinistryController,
    PeopleDashboardController,
    PermissionController,
    ProfileController as AdminProfileController,
    ReportController,
    SystemController,
    UserController
};

/*
|--------------------------------------------------------------------------
| Rotas da Área Administrativa
|--------------------------------------------------------------------------
|
| Rotas para o painel de administração. Totalmente refatoradas para
| seguir as melhores práticas, com agrupamento lógico e permissões.
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.access'])->group(function () {

    // --- Dashboard Principal ---
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- Perfil do Administrador ---
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
    });

    // --- Gestão de Pessoas ---
    Route::prefix('people')->name('people.')->middleware('permission:people.access')->group(function () {
        Route::get('/', [PeopleDashboardController::class, 'index'])->name('index');
        Route::resource('users', UserController::class)->except(['show']); // A view de 'show' é o perfil público
        Route::resource('ministries', MinistryController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('cargos', CargoController::class);
    });

    // --- Gestão Financeira ---
    Route::prefix('finance')->name('finance.')->middleware('permission:finance.access')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('dashboard');
        // Adicionar outras rotas financeiras aqui (ex: transacoes, relatorios)
    });

    // --- Escola Bíblica Dominical (EBD) ---
    Route::prefix('ebd')->name('ebd.')->middleware('permission:ebd.access')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.ebd.turmas.index'))->name('index');
        Route::resource('turmas', EbdTurmaController::class);
        Route::resource('turmas.users', EBD\TurmaUserController::class)->only(['store', 'destroy'])->names('turmas.users');
        Route::resource('grupos', EbdGrupoController::class)->except('index');
        Route::resource('grupos.users', EBD\GrupoUserController::class)->only(['store', 'destroy'])->names('grupos.users');
        // Adicionar rotas para Licoes, Aulas, etc. aqui
    });

    // --- Módulos Adicionais ---
    Route::resource('devotionals', DevocionalController::class)->middleware('permission:devotionals.access');
    Route::resource('council', ConselhoController::class)->middleware('permission:council.access');
    Route::resource('intercessor', IntercessorController::class)->middleware('permission:intercessor.access')->only(['index', 'show', 'update', 'destroy']);
    Route::resource('eventos', EventoController::class)->middleware('permission:eventos.access');
    Route::resource('eventos.inscricoes', EventoInscricaoController::class)->only(['destroy']);

    // --- Chat Administrativo ---
    Route::prefix('chat')->name('chat.')->middleware('permission:chat.access')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/manage', [ChatController::class, 'manage'])->name('manage');
        Route::resource('rooms', ChatController::class)->except(['index']);
    });

    // --- Gestão do Sistema ---
    Route::prefix('system')->name('system.')->middleware('permission:system.access')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('index');
        Route::get('/settings', [SystemController::class, 'settings'])->name('settings.index');
        Route::put('/settings', [SystemController::class, 'updateSettings'])->name('settings.update');
        Route::get('/home-config', [SystemController::class, 'homeConfig'])->name('home-config.index');
        Route::put('/home-config', [SystemController::class, 'updateHomeConfig'])->name('home-config.update');
        Route::post('/home-config/reset', [SystemController::class, 'resetHomeConfig'])->name('home-config.reset');
        Route::get('/logs', [SystemController::class, 'logs'])->name('logs.index');
        Route::delete('/logs', [SystemController::class, 'clearLogs'])->name('logs.clear');
        Route::get('/maintenance', [SystemController::class, 'maintenance'])->name('maintenance.index');
        Route::post('/maintenance/down', [SystemController::class, 'enableMaintenance'])->name('maintenance.down');
        Route::post('/maintenance/up', [SystemController::class, 'disableMaintenance'])->name('maintenance.up');
        Route::resource('permissions', PermissionController::class)->only(['index', 'edit', 'update']);
    });
});