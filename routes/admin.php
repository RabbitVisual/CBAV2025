<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    BirthdayController,
    CargoController,
    CepController,
    ChatController,
    ConselhoController,
    DashboardController as AdminDashboardController,
    DepartmentController,
    DevocionalController,
    DocumentoBaixaController,
    DocumentoDeclaracaoAnualController,
    EbdAlunoController,
    EbdAulaController,
    EbdAvaliacaoController,
    EbdAvaliacaoGrupoController,
    EbdCertificadoController,
    EBD\GruposEstudoController as AdminEbdGruposEstudoController,
    EbdLicaoController,
    EbdProfessorController,
    EbdQuizBiblicoController,
    EbdQuestaoController,
    EbdRelatorioController,
    EbdTurmaController,
    EventoController,
    EventoInscricaoController,
    FinanceController,
    IntercessorController,
    MemberController,
    MinistryController,
    PeopleDashboardController,
    PermissionController,
    ProfileController as AdminProfileController,
    ReportController,
    System\NotificationController as AdminSystemNotificationController,
    SystemController,
    UserController
};

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
    });

    // Gestão de Pessoas
    Route::prefix('people')->name('people.')->middleware('permission:people.access')->group(function () {
        Route::get('/', PeopleDashboardController::class)->name('index');
        Route::resource('members', MemberController::class);
        Route::resource('users', UserController::class);
        Route::resource('ministries', MinistryController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('cargos', CargoController::class);
        Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
    });

    // Gestão Financeira
    Route::prefix('finance')->name('finance.')->middleware('permission:finance.access')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('dashboard');
        Route::resource('documentos', DocumentoBaixaController::class);
        Route::resource('documentos-declaracao-anual', DocumentoDeclaracaoAnualController::class);
    });

    // Gestão do Sistema
    Route::prefix('system')->name('system.')->middleware('permission:system.access')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('index');
        Route::resource('notifications', AdminSystemNotificationController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::resource('permissions', PermissionController::class);
    });

    // Devocionais
    Route::resource('devotionals', DevocionalController::class)->middleware('permission:devotionals.access');
    Route::post('devotionals/batch', [DevocionalController::class, 'createBatch'])->name('devotionals.batch.create');
    Route.::post('devotionals/{devocional}/toggle', [DevocionalController::class, 'toggleStatus'])->name('devotionals.toggle');

    // Conselho
    Route::resource('council', ConselhoController::class)->middleware('permission:council.access');

    // Intercessor (Pedidos de Oração)
    Route::resource('intercessor', IntercessorController::class)->middleware('permission:intercessor.access')->only(['index', 'show', 'update', 'destroy']);

    // Eventos
    Route::resource('eventos', EventoController::class)->middleware('permission:eventos.access');
    Route::resource('eventos.inscricoes', EventoInscricaoController::class)->except(['index', 'show', 'create', 'edit']);

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/manage', [ChatController::class, 'manage'])->name('manage');
        Route::get('/stats', [ChatController::class, 'stats'])->name('stats');
        Route::resource('rooms', ChatController::class, ['except' => ['index']]);
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
        Route::get('relatorios', [EbdRelatorioController::class, 'index'])->name('relatorios.index');
    });
});