<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\{
    BibleController,
    ChatController,
    DashboardController,
    DevocionalController,
    DonationController,
    EbdAlunoDashboardController,
    EbdAulaController,
    EbdCertificadoController,
    EbdQuizController,
    EBD\GruposController as EbdGruposController,
    EbdLicaoController,
    EbdQuizBiblicoController,
    EbdTurmaController,
    EventoController,
    MessageController,
    MinistryController,
    PedidoOracaoController,
    ProfileController,
    PublicMemberController
};

/*
|--------------------------------------------------------------------------
| Rotas da Área de Membros
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role.redirect'])->prefix('member')->name('member.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil do Usuário
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/password', [ProfileController::class, 'changePassword'])->name('password.update');
    });

    // Recursos Principais
    Route::resource('pedidos-oracao', PedidoOracaoController::class);
    Route::resource('messages', MessageController::class)->except(['edit', 'update']);
    Route::resource('devotionals', DevocionalController::class)->only(['index', 'show']);
    Route::resource('ministries', MinistryController::class)->only(['index', 'show']);
    Route::resource('eventos', EventoController::class)->only(['index', 'show']);

    // Rotas específicas de Pedidos de Oração
    Route::post('pedidos-oracao/{pedido}/marcar-atendido', [PedidoOracaoController::class, 'marcarAtendido'])->name('pedidos-oracao.marcar-atendido');

    // Rotas específicas de Mensagens
    Route::post('messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');

    // Rotas de Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{room}', [ChatController::class, 'show'])->name('show');
        Route::post('/{room}/join', [ChatController::class, 'join'])->name('join');
        Route::post('/{room}/leave', [ChatController::class, 'leave'])->name('leave');
        Route::post('/{room}/send', [ChatController::class, 'send'])->name('send');
    });

    // Rotas da EBD
    Route::prefix('ebd')->name('ebd.')->middleware('can:member-access')->group(function () {
        Route::get('/', [EbdAlunoDashboardController::class, 'index'])->name('dashboard');
        Route::get('turmas', [EbdTurmaController::class, 'index'])->name('turmas.index');
        Route::get('turmas/{turma}', [EbdTurmaController::class, 'show'])->name('turmas.show');
        Route::get('licoes', [EbdLicaoController::class, 'index'])->name('licoes.index');
        Route::get('licoes/{licao}', [EbdLicaoController::class, 'show'])->name('licoes.show');
        Route::get('aulas', [EbdAulaController::class, 'index'])->name('aulas.index');
        Route::get('aulas/{aula}', [EbdAulaController::class, 'show'])->name('aulas.show');
        Route::get('certificados', [EbdCertificadoController::class, 'index'])->name('certificados.index');
        Route::get('certificados/{certificado}', [EbdCertificadoController::class, 'show'])->name('certificados.show');
        Route::get('certificados/{certificado}/download', [EbdCertificadoController::class, 'download'])->name('certificados.download');
        Route::get('quiz/{avaliacao}', [EbdQuizController::class, 'show'])->name('quiz.show');
        Route::post('quiz/{avaliacao}', [EbdQuizController::class, 'responder'])->name('quiz.responder');
        Route::get('quiz-biblico', [EbdQuizBiblicoController::class, 'index'])->name('quiz-biblico.index');
        Route::post('quiz-biblico/iniciar', [EbdQuizBiblicoController::class, 'iniciar'])->name('quiz-biblico.iniciar');
    });

    // Outras rotas...
    Route::get('public-members', [PublicMemberController::class, 'index'])->name('public-members.index');
    Route::get('public-members/{membro}', [PublicMemberController::class, 'show'])->name('public-members.show');
});