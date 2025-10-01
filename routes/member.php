<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\{
    DashboardController as MemberDashboardController,
    ProfileController as MemberProfileController,
    DonationController as MemberDonationController,
    MinistryController as MemberMinistryController,
    BibleController as MemberBibleController,
    NotificationController as MemberNotificationController,
    DevocionalController as MemberDevocionalController,
    PublicMemberController as MemberPublicMemberController,
    EventoController as MemberEventoController,
    MessageController as MemberMessageController,
    PedidoOracaoController as MemberPedidoOracaoController,
    ChatController as MemberChatController,
    EbdAlunoDashboardController,
    EbdTurmaController as MemberEbdTurmaController,
    EbdLicaoController as MemberEbdLicaoController,
    EbdAulaController as MemberEbdAulaController,
    EbdQuizController as MemberEbdQuizController,
    EbdCertificadoController as MemberEbdCertificadoController,
    EBD\GruposController as MemberEbdGruposController,
    EbdQuizBiblicoController as MemberEbdQuizBiblicoController
};

/*
|--------------------------------------------------------------------------
| Rotas da Área de Membros
|--------------------------------------------------------------------------
|
| Rotas protegidas para usuários autenticados (membros).
|
*/

Route::middleware(['auth', 'role.redirect'])->prefix('member')->name('member.')->group(function () {
    // Dashboard
    Route::get('/', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Perfil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [MemberProfileController::class, 'index'])->name('index');
        Route::get('/edit', [MemberProfileController::class, 'edit'])->name('edit');
        Route::put('/', [MemberProfileController::class, 'update'])->name('update');
        Route::get('/password', [MemberProfileController::class, 'changePasswordForm'])->name('password');
        Route::post('/password', [MemberProfileController::class, 'changePassword'])->name('password.update');
        Route::get('/photo', [MemberProfileController::class, 'photoForm'])->name('photo');
        Route::post('/photo/upload', [MemberProfileController::class, 'uploadPhoto'])->name('photo.upload');
        Route::delete('/photo', [MemberProfileController::class, 'deletePhoto'])->name('photo.delete');
    });

    // Doações
    Route::prefix('donations')->name('donations.')->middleware('check.payment.gateways')->group(function () {
        Route::get('/', [MemberDonationController::class, 'index'])->name('index');
        Route::get('/history', [MemberDonationController::class, 'history'])->name('history');
        Route::get('/campaigns', [MemberDonationController::class, 'campaigns'])->name('campaigns');
        Route::get('/campaigns/{campanha}', [MemberDonationController::class, 'showCampaign'])->name('campaigns.show');
        Route::get('/donate', [MemberDonationController::class, 'donate'])->name('donate');
        Route::post('/donate', [MemberDonationController::class, 'processDonation'])->name('donate.process');
        Route::get('/stripe', [MemberDonationController::class, 'showStripe'])->name('stripe.show');
        Route::get('/mercadopago', [MemberDonationController::class, 'showMercadoPago'])->name('mercadopago.show');
        Route::get('/pix', [MemberDonationController::class, 'showPix'])->name('pix.show');
        Route::post('/pix/verificar/{transacaoId}', [MemberDonationController::class, 'verificarPagamentoPix'])->name('pix.verificar');
        Route::get('/transaction/{transacaoId}/details', [MemberDonationController::class, 'showTransactionDetails'])->name('transaction.details');
        Route::get('/transaction/{transacaoId}/comprovante', [MemberDonationController::class, 'downloadComprovante'])->name('transaction.comprovante');
        Route::get('/verificar-comprovante', [MemberDonationController::class, 'verificarComprovante'])->name('verificar-comprovante');
    });

    // Ministérios
    Route::prefix('ministries')->name('ministries.')->group(function () {
        Route::get('/', [MemberMinistryController::class, 'index'])->name('index');
        Route::get('/available', [MemberMinistryController::class, 'available'])->name('available');
        Route::get('/{ministerio}', [MemberMinistryController::class, 'show'])->name('show');
        Route::get('/{ministerio}/request', [MemberMinistryController::class, 'requestForm'])->name('request.form');
        Route::post('/{ministerio}/request', [MemberMinistryController::class, 'requestParticipation'])->name('request');
        Route::delete('/request/{solicitacao}', [MemberMinistryController::class, 'cancelRequest'])->name('cancel');
        Route::delete('/{ministerio}/leave', [MemberMinistryController::class, 'leaveMinistry'])->name('leave');
        Route::get('/participations', [MemberMinistryController::class, 'myParticipations'])->name('participations');
        Route::get('/activities', [MemberMinistryController::class, 'activities'])->name('activities');
    });

    // Bíblia
    Route::prefix('bible')->name('bible.')->group(function () {
        Route::get('/', [MemberBibleController::class, 'index'])->name('index');
        Route::post('/search', [MemberBibleController::class, 'searchByReference'])->name('search');
        Route::match(['get', 'post'], '/search-book', [MemberBibleController::class, 'searchByBookChapter'])->name('search-book');
        Route::get('/search-keyword', [MemberBibleController::class, 'searchByKeyword'])->name('search-keyword');
        Route::post('/search-keyword', [MemberBibleController::class, 'searchByKeyword'])->name('search-keyword.post');
        Route::get('/read', [MemberBibleController::class, 'readChapter'])->name('read');
        Route::get('/chapter', [MemberBibleController::class, 'readChapter'])->name('chapter');
        Route::get('/random-verse', [MemberBibleController::class, 'randomVerse'])->name('random-verse');
        Route::get('/verse-of-day', [MemberBibleController::class, 'verseOfTheDay'])->name('verse-of-day');
        Route::post('/change-version', [MemberBibleController::class, 'changeVersion'])->name('change-version');
        Route::get('/status', [MemberBibleController::class, 'checkStatus'])->name('status');
        Route::get('/favorites', [MemberBibleController::class, 'favorites'])->name('favorites');
        Route::post('/favorites/add', [MemberBibleController::class, 'addToFavorites'])->name('favorites.add');
        Route::delete('/favorites/remove', [MemberBibleController::class, 'removeFromFavorites'])->name('favorites.remove');
        Route::delete('/favorites/clear', [MemberBibleController::class, 'clearFavorites'])->name('favorites.clear');
        Route::post('/share', [MemberBibleController::class, 'shareVerse'])->name('share');
        Route::get('/shared', [MemberBibleController::class, 'sharedVerse'])->name('shared');
        Route::get('/history', [MemberBibleController::class, 'readingHistory'])->name('history');
        Route::post('/history/save', [MemberBibleController::class, 'saveToHistory'])->name('history.save');
    });

    // Notificações
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [MemberNotificationController::class, 'index'])->name('index');
        Route::get('/header', [MemberNotificationController::class, 'getHeaderNotifications'])->name('header');
        Route::get('/count-unread', [MemberNotificationController::class, 'countUnread'])->name('count-unread');
        Route::post('/settings', [MemberNotificationController::class, 'updateSettings'])->name('settings');
        Route::post('/mark-all-read', [MemberNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/clear-read', [MemberNotificationController::class, 'clearRead'])->name('clear-read');
        Route::post('/{id}/read', [MemberNotificationController::class, 'markAsRead'])->name('read');
        Route::post('/{id}/unread', [MemberNotificationController::class, 'markAsUnread'])->name('unread');
        Route::post('/{id}/star', [MemberNotificationController::class, 'toggleStar'])->name('star');
        Route::post('/{id}/archive', [MemberNotificationController::class, 'archive'])->name('archive');
        Route::post('/{id}/action', [MemberNotificationController::class, 'recordAction'])->name('action');
        Route::delete('/{id}', [MemberNotificationController::class, 'destroy'])->name('delete');
    });

    // Mensagens Privadas
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MemberMessageController::class, 'index'])->name('index');
        Route::get('/compose', [MemberMessageController::class, 'compose'])->name('compose');
        Route::post('/send', [MemberMessageController::class, 'send'])->name('send');
        Route::get('/{message}', [MemberMessageController::class, 'show'])->name('show');
        Route::post('/{message}/reply', [MemberMessageController::class, 'reply'])->name('reply');
        Route::delete('/{message}', [MemberMessageController::class, 'destroy'])->name('destroy');
        Route::post('/{message}/mark-read', [MemberMessageController::class, 'markAsRead'])->name('mark-read');
    });

    // Devocionais
    Route::prefix('devotionals')->name('devotionals.')->group(function () {
        Route::get('/', [MemberDevocionalController::class, 'index'])->name('index');
        Route::get('/search', [MemberDevocionalController::class, 'search'])->name('search');
        Route::get('/{devocional}', [MemberDevocionalController::class, 'show'])->name('show');
        Route::get('/random-verse', [MemberDevocionalController::class, 'randomVerse'])->name('random-verse');
        Route::get('/verse', [MemberDevocionalController::class, 'searchVerse'])->name('verse');
        Route::get('/keyword-search', [MemberDevocionalController::class, 'searchByKeyword'])->name('keyword-search');
        Route::get('/chapter', [MemberDevocionalController::class, 'readChapter'])->name('chapter');
        Route::get('/favorites', [MemberDevocionalController::class, 'favorites'])->name('favorites');
        Route::post('/add-to-favorites', [MemberDevocionalController::class, 'addToFavorites'])->name('add-to-favorites');
        Route::delete('/remove-from-favorites', [MemberDevocionalController::class, 'removeFromFavorites'])->name('remove-from-favorites');
        Route::get('/history', [MemberDevocionalController::class, 'readingHistory'])->name('history');
        Route::post('/save-to-history', [MemberDevocionalController::class, 'saveToHistory'])->name('save-to-history');
        Route::delete('/remove-from-history', [MemberDevocionalController::class, 'removeFromHistory'])->name('remove-from-history');
        Route::post('/share-verse', [MemberDevocionalController::class, 'shareVerse'])->name('share-verse');
        Route::get('/check-bible-status', [MemberDevocionalController::class, 'checkBibleStatus'])->name('check-bible-status');
        Route::post('/change-version', [MemberDevocionalController::class, 'changeVersion'])->name('change-version');
    });

    // Membros Públicos
    Route::prefix('public-members')->name('public-members.')->group(function () {
        Route::get('/', [MemberPublicMemberController::class, 'index'])->name('index');
        Route::get('/search', [MemberPublicMemberController::class, 'search'])->name('search');
        Route::get('/{membro}', [MemberPublicMemberController::class, 'show'])->name('show');
    });

    // Pedidos de Oração
    Route::prefix('pedidos-oracao')->name('pedidos-oracao.')->group(function () {
        Route::get('/', [MemberPedidoOracaoController::class, 'index'])->name('index');
        Route::get('/create', [MemberPedidoOracaoController::class, 'create'])->name('create');
        Route::post('/', [MemberPedidoOracaoController::class, 'store'])->name('store');
        Route::get('/{pedido}', [MemberPedidoOracaoController::class, 'show'])->name('show');
        Route::get('/{pedido}/edit', [MemberPedidoOracaoController::class, 'edit'])->name('edit');
        Route::put('/{pedido}', [MemberPedidoOracaoController::class, 'update'])->name('update');
        Route::delete('/{pedido}', [MemberPedidoOracaoController::class, 'destroy'])->name('destroy');
        Route::post('/{pedido}/marcar-atendido', [MemberPedidoOracaoController::class, 'marcarAtendido'])->name('marcar-atendido');
        Route::post('/{pedido}/participar-intercessao', [MemberPedidoOracaoController::class, 'participarIntercessao'])->name('participar-intercessao');
        Route::get('/answered', [MemberPedidoOracaoController::class, 'answered'])->name('answered');
    });

    // Eventos
    Route::prefix('eventos')->name('eventos.')->group(function () {
        Route::get('/', [MemberEventoController::class, 'index'])->name('index');
        Route::get('/minhas-inscricoes', [MemberEventoController::class, 'minhasInscricoes'])->name('minhas-inscricoes');
        Route::get('/{evento}', [MemberEventoController::class, 'show'])->name('show');
        Route::get('/{evento}/inscrever', [MemberEventoController::class, 'inscrever'])->name('inscrever');
        Route::post('/{evento}/inscrever', [MemberEventoController::class, 'processarInscricao'])->name('processar-inscricao');
        Route::get('/{evento}/pagamento/{inscricao}', [MemberEventoController::class, 'pagamento'])->name('pagamento');
        Route::post('/{evento}/pagamento/{inscricao}', [MemberEventoController::class, 'processarPagamento'])->name('processar-pagamento');
        Route::get('/inscricao/{inscricao}/confirmacao', [MemberEventoController::class, 'confirmacao'])->name('inscricao.confirmacao');
        Route::post('/inscricao/{inscricao}/cancelar', [MemberEventoController::class, 'cancelarInscricao'])->name('cancelar-inscricao');
        Route::get('/inscricao/{inscricao}/certificado', [MemberEventoController::class, 'downloadCertificado'])->name('download-certificado');
    });

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [MemberChatController::class, 'index'])->name('index');
        Route::get('/stats', [MemberChatController::class, 'getChatStats'])->name('stats');
        Route::get('/{room}', [MemberChatController::class, 'show'])->name('show');
        Route::post('/{room}/join', [MemberChatController::class, 'join'])->name('join');
        Route::post('/{room}/leave', [MemberChatController::class, 'leave'])->name('leave');
        Route::post('/{room}/send', [MemberChatController::class, 'send'])->name('send');
        Route::post('/{room}/read', [MemberChatController::class, 'read'])->name('read');
        Route::get('/{room}/messages', [MemberChatController::class, 'messages'])->name('messages');
    });

    // EBD - Membro
    Route::prefix('ebd')->name('ebd.')->middleware('can:member-access')->group(function () {
        Route::get('/', [EbdAlunoDashboardController::class, 'index'])->name('dashboard');
        Route::get('turmas', [MemberEbdTurmaController::class, 'index'])->name('turmas.index');
        Route::get('turmas/{turma}', [MemberEbdTurmaController::class, 'show'])->name('turmas.show');
        Route::get('licoes', [MemberEbdLicaoController::class, 'index'])->name('licoes.index');
        Route::get('licoes/{licao}', [MemberEbdLicaoController::class, 'show'])->name('licoes.show');
        Route::get('aulas', [MemberEbdAulaController::class, 'index'])->name('aulas.index');
        Route::get('aulas/{aula}', [MemberEbdAulaController::class, 'show'])->name('aulas.show');
        Route::get('avaliacoes/{avaliacao}/quiz', [MemberEbdQuizController::class, 'show'])->name('quiz.show');
        Route::post('avaliacoes/{avaliacao}/quiz', [MemberEbdQuizController::class, 'responder'])->name('quiz.responder');
        Route::get('grupos', [MemberEbdGruposController::class, 'index'])->name('grupos.index');
        Route::get('grupos/{grupo}', [MemberEbdGruposController::class, 'show'])->name('grupos.show');
        Route::post('grupos/{grupo}/entrar', [MemberEbdGruposController::class, 'entrar'])->name('grupos.entrar');
        Route::delete('grupos/{grupo}/sair', [MemberEbdGruposController::class, 'sair'])->name('grupos.sair');
        Route::get('avaliacoes/{avaliacao}/participar', [MemberEbdGruposController::class, 'avaliar'])->name('avaliacoes.participar');
        Route::get('avaliacoes/{avaliacao}/questao/{questaoOrdem}', [MemberEbdGruposController::class, 'avaliar'])->name('avaliacoes.questao');
        Route::post('avaliacoes/{avaliacao}/questao/{questao}/responder', [MemberEbdGruposController::class, 'responder'])->name('avaliacoes.responder');
        Route::post('avaliacoes/{avaliacao}/questao/{questao}/contribuir', [MemberEbdGruposController::class, 'contribuir'])->name('avaliacoes.contribuir');
        Route::post('avaliacoes/{avaliacao}/finalizar', [MemberEbdGruposController::class, 'finalizar'])->name('avaliacoes.finalizar');
        Route::get('certificados', [MemberEbdCertificadoController::class, 'index'])->name('certificados.index');
        Route::get('certificados/{certificado}', [MemberEbdCertificadoController::class, 'show'])->name('certificados.show');
        Route::get('certificados/{certificado}/download', [MemberEbdCertificadoController::class, 'download'])->name('certificados.download');
        Route::prefix('quiz-biblico')->name('quiz-biblico.')->group(function () {
            Route::get('/', [MemberEbdQuizBiblicoController::class, 'index'])->name('index');
            Route::post('/iniciar', [MemberEbdQuizBiblicoController::class, 'iniciar'])->name('iniciar');
            Route::get('/jogar/{sessao}', [MemberEbdQuizBiblicoController::class, 'jogar'])->name('jogar');
            Route::post('/responder/{sessao}', [MemberEbdQuizBiblicoController::class, 'responder'])->name('responder');
            Route::get('/resultado/{sessao}', [MemberEbdQuizBiblicoController::class, 'resultado'])->name('resultado');
            Route::get('/historico', [MemberEbdQuizBiblicoController::class, 'historico'])->name('historico');
        });
    });
});