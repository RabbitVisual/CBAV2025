<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    PeopleDashboardController,
    ReportController,
    MemberController,
    UserController,
    MinistryController,
    DepartmentController,
    CargoController,
    BirthdayController,
    FinanceController,
    SystemController,
    DevocionalController,
    ConselhoController,
    ProfileController as AdminProfileController,
    PermissionController,
    EventoController,
    EventoInscricaoController,
    ChatController,
    CepController,
    IntercessorController,
    DocumentoBaixaController,
    DocumentoDeclaracaoAnualController
};
use App\Http\Controllers\Admin\System\NotificationController as AdminSystemNotificationController;
use App\Http\Controllers\Admin\EbdTurmaController;
use App\Http\Controllers\Admin\EbdProfessorController;
use App\Http\Controllers\Admin\EbdAlunoController;
use App\Http\Controllers\Admin\EbdLicaoController;
use App\Http\Controllers\Admin\EbdAulaController;
use App\Http\Controllers\Admin\EbdAvaliacaoController;
use App\Http\Controllers\Admin\EbdQuestaoController;
use App\Http\Controllers\Admin\EbdCertificadoController;
use App\Http\Controllers\Admin\EbdRelatorioController;
use App\Http\Controllers\Admin\EBD\GruposEstudoController as AdminEbdGruposEstudoController;
use App\Http\Controllers\Admin\EbdAvaliacaoGrupoController;
use App\Http\Controllers\Admin\EbdQuizBiblicoController;

/*
|--------------------------------------------------------------------------
| Rotas da Área Administrativa
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.access'])->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [AdminProfileController::class, 'changePassword'])->name('change-password');
    });

    // Gestão de Pessoas
    Route::prefix('people')->name('people.')->middleware('permission:people.access')->group(function () {
        Route::get('/', PeopleDashboardController::class)->name('index');
        Route::resource('members', MemberController::class);
        Route::resource('users', UserController::class);
        Route::resource('ministries', MinistryController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('cargos', CargoController::class);
        Route::resource('ceps', CepController::class);
        Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
    });

    // Gestão Financeira
    Route::prefix('finance')->name('finance.')->middleware('permission:finance.access')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('dashboard');
        Route::resource('transactions', FinanceController::class, ['as' => 'transactions']); // Simplificar se FinanceController for refatorado
        Route::resource('campaigns', FinanceController::class, ['as' => 'campaigns']); // Simplificar se FinanceController for refatorado
        Route::resource('documentos', DocumentoBaixaController::class);
        Route::resource('documentos-declaracao-anual', DocumentoDeclaracaoAnualController::class);
    });

    // Gestão do Sistema
    Route::prefix('system')->name('system.')->middleware('permission:system.access')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('index');
        Route::resource('settings', SystemController::class, ['as' => 'settings', 'only' => ['index', 'update']]);
        Route::resource('home-config', SystemController::class, ['as' => 'home-config', 'only' => ['index', 'update']]);
        Route::resource('notifications', AdminSystemNotificationController::class);
        Route::resource('permissions', PermissionController::class);
    });

    // Devocionais
    Route::prefix('devotionals')->name('devotionals.')->middleware('permission:devotionals.access')->group(function () {
        Route::resource('/', DevocionalController::class)->parameters(['' => 'devocional']);
        Route::post('/batch', [DevocionalController::class, 'createBatch'])->name('batch.create');
        Route::post('/{devocional}/toggle', [DevocionalController::class, 'toggleStatus'])->name('toggle');
    });

    // Conselho
    Route::prefix('council')->name('council.')->middleware('permission:council.access')->group(function () {
        Route::get('/', [ConselhoController::class, 'dashboard'])->name('dashboard');
        Route::resource('reunioes', ConselhoController::class)->except(['destroy']);
        Route::post('reunioes/{conselho}/iniciar', [ConselhoController::class, 'iniciar'])->name('reunioes.iniciar');
        Route::post('reunioes/{conselho}/finalizar', [ConselhoController::class, 'finalizar'])->name('reunioes.finalizar');
        Route::post('reunioes/{conselho}/cancelar', [ConselhoController::class, 'cancelar'])->name('reunioes.cancelar');
        // Adicionar outras rotas do conselho (pautas, votações, etc.) aqui
    });

    // Intercessor (Pedidos de Oração)
    Route::resource('intercessor', IntercessorController::class)->middleware('permission:intercessor.access');

    // Eventos
    Route::prefix('eventos')->name('eventos.')->middleware('permission:eventos.access')->group(function () {
        Route::resource('/', EventoController::class)->parameters(['' => 'evento']);
        Route::resource('/{evento}/inscricoes', EventoInscricaoController::class)->except(['index', 'show', 'create', 'edit']);
    });

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/manage', [ChatController::class, 'manage'])->name('manage');
        Route::get('/stats', [ChatController::class, 'stats'])->name('stats');
        Route::resource('rooms', ChatController::class)->except(['index']);
    });

    // EBD - ADMIN
    Route::prefix('ebd')->name('ebd.')->middleware('permission:ebd.access')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.ebd.turmas.index'))->name('dashboard');
        Route::resource('turmas', EbdTurmaController::class);
        Route::resource('professores', EbdProfessorController::class);
        Route::resource('alunos', EbdAlunoController::class);
        Route::resource('licoes', EbdLicaoController::class);
        Route::resource('aulas', EbdAulaController::class);
        Route::resource('avaliacoes', EbdAvaliacaoController::class);
        Route::resource('questoes', EbdQuestaoController::class);
        Route::resource('certificados', EbdCertificadoController::class);
        Route::resource('grupos-estudo', AdminEbdGruposEstudoController::class);
        Route::resource('avaliacoes-grupo', AdminEbdAvaliacaoGrupoController::class);
        Route::resource('quiz-biblico', AdminEbdQuizBiblicoController::class);
        Route::get('relatorios', [AdminEbdRelatorioController::class, 'index'])->name('relatorios.index');
    });
});