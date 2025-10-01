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
use App\Http\Controllers\Admin\EbdTurmaController as AdminEbdTurmaController;
use App\Http\Controllers\Admin\EbdProfessorController as AdminEbdProfessorController;
use App\Http\Controllers\Admin\EbdAlunoController as AdminEbdAlunoController;
use App\Http\Controllers\Admin\EbdLicaoController as AdminEbdLicaoController;
use App\Http\Controllers\Admin\EbdAulaController as AdminEbdAulaController;
use App\Http\Controllers\Admin\EbdAvaliacaoController as AdminEbdAvaliacaoController;
use App\Http\Controllers\Admin\EbdQuestaoController as AdminEbdQuestaoController;
use App\Http\Controllers\Admin\EbdCertificadoController as AdminEbdCertificadoController;
use App\Http\Controllers\Admin\EbdRelatorioController as AdminEbdRelatorioController;
use App\Http\Controllers\Admin\EBD\GruposEstudoController as AdminEbdGruposEstudoController;
use App\Http\Controllers\Admin\EbdAvaliacaoGrupoController as AdminEbdAvaliacaoGrupoController;
use App\Http\Controllers\Admin\EbdQuizBiblicoController as AdminEbdQuizBiblicoController;

/*
|--------------------------------------------------------------------------
| Rotas da Área Administrativa
|--------------------------------------------------------------------------
|
| Rotas protegidas para usuários com permissões de administrador.
|
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.access'])->group(function () {

    // Dashboard principal
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Perfil do Administrador
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'index'])->name('index');
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::post('/change-password', [AdminProfileController::class, 'changePassword'])->name('change-password');
        Route::delete('/delete-photo', [AdminProfileController::class, 'deletePhoto'])->name('delete-photo');
    });

    // Gestão de Pessoas
    Route::prefix('people')->name('people.')->middleware('permission:people.access')->group(function () {
        Route::get('/', PeopleDashboardController::class)->name('index');

        // Membros (agora em seu próprio controlador)
        Route::resource('members', MemberController::class)->names('members');
        Route::get('members/{membro}/ficha', [MemberController::class, 'memberCard'])->name('members.ficha');
        Route::get('members/export', [MemberController::class, 'export'])->name('members.export');
        Route::get('members/import', [MemberController::class, 'import'])->name('members.import');
        Route::post('members/import', [MemberController::class, 'processImport'])->name('members.processImport');

        // Usuários (agora em seu próprio controlador)
        Route::resource('users', UserController::class)->names('users');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::get('users/export', [UserController::class, 'export'])->name('users.export');

        // Ministérios (agora em seu próprio controlador)
        Route::resource('ministries', MinistryController::class)->names('ministries');
        Route::get('ministries/{ministerio}/export', [MinistryController::class, 'exportMinistry'])->name('ministries.export');
        Route::get('ministries/export/all', [MinistryController::class, 'export'])->name('ministries.export.all');

        // Departamentos (agora em seu próprio controlador)
        Route::resource('departments', DepartmentController::class)->names('departments');

        // Cargos (agora em seu próprio controlador)
        Route::resource('cargos', CargoController::class)->names('cargos');

        // Aniversariantes (agora em seu próprio controlador)
        Route::get('birthdays', [BirthdayController::class, 'index'])->name('birthdays.index');
        Route::get('birthdays/export', [BirthdayController::class, 'export'])->name('birthdays.export');

        // Gestão de CEPs
        Route::prefix('ceps')->name('ceps.')->group(function () {
            Route::get('/', [CepController::class, 'index'])->name('index');
            Route::get('/create', [CepController::class, 'create'])->name('create');
            Route::post('/', [CepController::class, 'store'])->name('store');
            Route::get('/{cep}', [CepController::class, 'show'])->name('show');
            Route::get('/{cep}/edit', [CepController::class, 'edit'])->name('edit');
            Route::put('/{cep}', [CepController::class, 'update'])->name('update');
            Route::delete('/{cep}', [CepController::class, 'destroy'])->name('destroy');
            Route::get('/buscar/{cep}', [CepController::class, 'buscar'])->name('buscar');
        });

        // Relatórios
        Route::prefix('reports')->name('reports.')->middleware('permission:reports.access')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
            Route::get('/export-all', [ReportController::class, 'exportAll'])->name('export-all');
        });
    });

    // Gestão Financeira
    Route::prefix('finance')->name('finance.')->middleware('permission:finance.access')->group(function () {
        Route::get('/', [FinanceController::class, 'index'])->name('index');
        Route::get('/dashboard', [FinanceController::class, 'index'])->name('dashboard');
        Route::prefix('transactions')->name('transactions.')->middleware('permission:transactions.access')->group(function () {
            Route::get('/', [FinanceController::class, 'transactions'])->name('index');
            Route::get('/create', [FinanceController::class, 'createTransaction'])->name('create');
            Route::post('/', [FinanceController::class, 'storeTransaction'])->name('store');
            Route::get('/export', [FinanceController::class, 'exportTransactions'])->name('export');
            Route::get('/{transacao}', [FinanceController::class, 'showTransaction'])->name('show');
            Route::get('/{transacao}/edit', [FinanceController::class, 'editTransaction'])->name('edit');
            Route::put('/{transacao}', [FinanceController::class, 'updateTransaction'])->name('update');
            Route::delete('/{transacao}', [FinanceController::class, 'deleteTransaction'])->name('delete');
            Route::get('/{transacao}/delete', [FinanceController::class, 'deleteTransactionGet'])->name('delete.get')->middleware('permission:transactions.delete');
            Route::post('/bulk-delete', [FinanceController::class, 'bulkDeleteTransactions'])->name('bulk-delete')->middleware('permission:transactions.delete');
            Route::get('/{transacao}/comprovante', [FinanceController::class, 'exportTransactionComprovante'])->name('comprovante');
        });
        Route::prefix('campaigns')->name('campaigns.')->middleware('permission:campaigns.access')->group(function () {
            Route::get('/', [FinanceController::class, 'campaigns'])->name('index');
            Route::get('/create', [FinanceController::class, 'createCampaign'])->name('create');
            Route::post('/', [FinanceController::class, 'storeCampaign'])->name('store');
            Route::get('/{campanha}', [FinanceController::class, 'showCampaign'])->name('show');
            Route::get('/{campanha}/edit', [FinanceController::class, 'editCampaign'])->name('edit');
            Route::put('/{campanha}', [FinanceController::class, 'updateCampaign'])->name('update');
            Route::delete('/{campanha}', [FinanceController::class, 'deleteCampaign'])->name('delete');
            Route::get('/{campanha}/transactions', [FinanceController::class, 'campaignTransactions'])->name('transactions');
            Route::get('/{campanha}/export-report', [FinanceController::class, 'exportCampaignReport'])->name('export-report');
            Route::post('/batch', [FinanceController::class, 'createBatchCampaigns'])->name('batch');
        });
        Route::prefix('reports')->name('reports.')->middleware('permission:reports.access')->group(function () {
            Route::get('/', [FinanceController::class, 'reports'])->name('index');
            Route::get('/export', [FinanceController::class, 'exportReports'])->name('export');
        });
        Route::prefix('settings')->name('settings.')->middleware('permission:settings.access')->group(function () {
            Route::get('/', [FinanceController::class, 'settings'])->name('index');
            Route::post('/', [FinanceController::class, 'updateSettings'])->name('update');
        });
        Route::prefix('documentos')->name('documentos.')->middleware('permission:finance.access')->group(function () {
            Route::get('/', [DocumentoBaixaController::class, 'index'])->name('index');
            Route::get('/create', [DocumentoBaixaController::class, 'create'])->name('create');
            Route::post('/', [DocumentoBaixaController::class, 'store'])->name('store');
            Route::get('/export', [DocumentoBaixaController::class, 'exportar'])->name('export');
            Route::get('/{documento}', [DocumentoBaixaController::class, 'show'])->name('show');
            Route::get('/{documento}/edit', [DocumentoBaixaController::class, 'edit'])->name('edit');
            Route::put('/{documento}', [DocumentoBaixaController::class, 'update'])->name('update');
            Route::delete('/{documento}', [DocumentoBaixaController::class, 'destroy'])->name('destroy');
            Route::post('/{documento}/marcar-pago', [DocumentoBaixaController::class, 'marcarComoPago'])->name('marcar-pago');
            Route::get('/{documento}/pdf', [DocumentoBaixaController::class, 'gerarPdf'])->name('pdf');
            Route::get('/{documento}/codigo-barras', [DocumentoBaixaController::class, 'gerarCodigoBarras'])->name('codigo-barras');
            Route::get('/{documento}/validar', [DocumentoBaixaController::class, 'validarDocumento'])->name('validar');
            Route::get('/{documento}/calcular-multa-juros', [DocumentoBaixaController::class, 'calcularMultaJuros'])->name('calcular-multa-juros');
        });
        Route::prefix('documentos-declaracao-anual')->name('documentos-declaracao-anual.')->group(function () {
            Route::get('/', [DocumentoDeclaracaoAnualController::class, 'index'])->name('index');
            Route::get('/create', [DocumentoDeclaracaoAnualController::class, 'create'])->name('create');
            Route::post('/', [DocumentoDeclaracaoAnualController::class, 'store'])->name('store');
            Route::get('/export', [DocumentoDeclaracaoAnualController::class, 'export'])->name('export');
            Route::get('/estatisticas/por-igreja', [DocumentoDeclaracaoAnualController::class, 'estatisticasPorIgreja'])->name('estatisticas.por-igreja');
            Route::get('/{documento}', [DocumentoDeclaracaoAnualController::class, 'show'])->name('show');
            Route::get('/{documento}/edit', [DocumentoDeclaracaoAnualController::class, 'edit'])->name('edit');
            Route::put('/{documento}', [DocumentoDeclaracaoAnualController::class, 'update'])->name('update');
            Route::delete('/{documento}', [DocumentoDeclaracaoAnualController::class, 'destroy'])->name('destroy');
            Route::get('/{documento}/pdf', [DocumentoDeclaracaoAnualController::class, 'pdf'])->name('pdf');
            Route::get('/{documento}/codigo-barras', [DocumentoDeclaracaoAnualController::class, 'codigoBarras'])->name('codigo-barras');
            Route::get('/{documento}/qr-code', [DocumentoDeclaracaoAnualController::class, 'qrCode'])->name('qr-code');
            Route::get('/{documento}/calcular-multa-juros', [DocumentoDeclaracaoAnualController::class, 'calcularMultaJuros'])->name('calcular-multa-juros');
            Route::post('/{documento}/validar', [DocumentoDeclaracaoAnualController::class, 'validar'])->name('validar');
        });
    });

    // Gestão do Sistema
    Route::prefix('system')->name('system.')->middleware('permission:system.access')->group(function () {
        Route::get('/', [SystemController::class, 'index'])->name('index');
        Route::get('/dashboard', [SystemController::class, 'index'])->name('dashboard');
        Route::prefix('settings')->name('settings.')->middleware('permission:settings.access')->group(function () {
            Route::get('/', [SystemController::class, 'settings'])->name('index');
            Route::put('/', [SystemController::class, 'updateSettings'])->name('update');
            Route::post('/test-email', [SystemController::class, 'testEmail'])->name('test-email');
        });
        Route::prefix('home-config')->name('home-config.')->middleware('permission:settings.access')->group(function () {
            Route::get('/', [SystemController::class, 'homeConfig'])->name('index');
            Route::put('/', [SystemController::class, 'updateHomeConfig'])->name('update');
            Route::post('/reset', [SystemController::class, 'resetHomeConfig'])->name('reset');
            Route::get('/test', [SystemController::class, 'test'])->name('test');
        });
        Route::prefix('notifications')->name('notifications.')->middleware('permission:notifications.access')->group(function () {
            Route::get('/', [AdminSystemNotificationController::class, 'index'])->name('index');
            Route::get('/create', [AdminSystemNotificationController::class, 'create'])->name('create');
            Route::post('/', [AdminSystemNotificationController::class, 'store'])->name('store');
            Route::get('/{notificacao}', [AdminSystemNotificationController::class, 'show'])->name('show');
            Route::get('/{notificacao}/edit', [AdminSystemNotificationController::class, 'edit'])->name('edit');
            Route::put('/{notificacao}', [AdminSystemNotificationController::class, 'update'])->name('update');
            Route::delete('/{notificacao}', [AdminSystemNotificationController::class, 'destroy'])->name('delete');
            Route::post('/{notificacao}/send', [AdminSystemNotificationController::class, 'send'])->name('send');
            Route::post('/test', [AdminSystemNotificationController::class, 'test'])->name('test');
            Route::post('/bulk-delete', [AdminSystemNotificationController::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-send', [AdminSystemNotificationController::class, 'bulkSend'])->name('bulk-send');
            Route::get('/export', [AdminSystemNotificationController::class, 'export'])->name('export');
        });
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [SystemController::class, 'logs'])->name('index');
            Route::post('/clear', [SystemController::class, 'clearLogs'])->name('clear');
            Route::post('/clear-old', [SystemController::class, 'clearOldLogs'])->name('clear-old');
            Route::get('/export', [SystemController::class, 'exportLogs'])->name('export');
            Route::get('/show', [SystemController::class, 'showLog'])->name('show');
        });
        Route::prefix('maintenance')->name('maintenance.')->middleware('permission:system.access')->group(function () {
            Route::get('/', [SystemController::class, 'maintenance'])->name('index');
            Route::post('/backup', [SystemController::class, 'backup'])->name('backup');
            Route::post('/cache', [SystemController::class, 'clearCache'])->name('cache');
            Route::post('/enable', [SystemController::class, 'enableMaintenance'])->name('enable');
            Route::post('/disable', [SystemController::class, 'disableMaintenance'])->name('disable');
        });
        Route::prefix('cache')->name('cache.')->group(function () {
            Route::post('/clear', [SystemController::class, 'clearCache'])->name('clear');
            Route::post('/test', [SystemController::class, 'testCache'])->name('test');
        });
        Route::prefix('backup')->name('backup.')->group(function () {
            Route::post('/run', [SystemController::class, 'runBackup'])->name('run');
            Route::get('/list', [SystemController::class, 'listBackups'])->name('list');
            Route::post('/test', [SystemController::class, 'testBackup'])->name('test');
            Route::post('/clean', [SystemController::class, 'cleanOldBackups'])->name('clean');
        });
        Route::post('/test', [SystemController::class, 'test'])->name('test');
        Route::post('/clear-cache', [SystemController::class, 'clearCache'])->name('clearCache');
    });

    // Devocionais
    Route::prefix('devotionals')->name('devotionals.')->middleware('permission:devotionals.access')->group(function () {
        Route::get('/', [DevocionalController::class, 'index'])->name('index');
        Route::get('/create', [DevocionalController::class, 'create'])->name('create');
        Route::post('/', [DevocionalController::class, 'store'])->name('store');
        Route::get('/preview', [DevocionalController::class, 'preview'])->name('preview');
        Route::get('/batch', [DevocionalController::class, 'batchForm'])->name('batch');
        Route::post('/batch', [DevocionalController::class, 'createBatch'])->name('batch.create');
        Route::get('/status', [DevocionalController::class, 'status'])->name('status');
        Route::get('/export', [DevocionalController::class, 'export'])->name('export');
        Route::post('/buscar-versiculo-offline', [DevocionalController::class, 'buscarVersiculoOffline'])->name('buscar-versiculo-offline');
        Route::post('/buscar-versiculo-aleatorio', [DevocionalController::class, 'buscarVersiculoAleatorio'])->name('buscar-versiculo-aleatorio');
        Route::post('/buscar-por-palavra-chave', [DevocionalController::class, 'buscarPorPalavraChave'])->name('buscar-por-palavra-chave');
        Route::get('/{devocional}', [DevocionalController::class, 'show'])->name('show');
        Route::get('/{devocional}/edit', [DevocionalController::class, 'edit'])->name('edit');
        Route::put('/{devocional}', [DevocionalController::class, 'update'])->name('update');
        Route::delete('/{devocional}', [DevocionalController::class, 'destroy'])->name('delete');
        Route::post('/{devocional}/toggle', [DevocionalController::class, 'toggleStatus'])->name('toggle');
        Route::post('/{devocional}/duplicate', [DevocionalController::class, 'duplicate'])->name('duplicate');
    });

    // Conselho
    Route::prefix('council')->name('council.')->middleware('permission:council.access')->group(function () {
        Route::get('/', [ConselhoController::class, 'dashboard'])->name('dashboard');
        Route::get('/reunioes', [ConselhoController::class, 'index'])->name('index');
        Route::get('/create', [ConselhoController::class, 'create'])->name('create');
        Route::post('/', [ConselhoController::class, 'store'])->name('store');
        Route::get('/settings', [ConselhoController::class, 'settings'])->name('settings');
        Route::put('/settings', [ConselhoController::class, 'updateSettings'])->name('settings.update');
        Route::get('/voting/history', [ConselhoController::class, 'votingHistory'])->name('voting.history');
        Route::get('/export', [ConselhoController::class, 'exportar'])->name('export');
        Route::prefix('agenda/templates')->name('agenda.template.')->group(function () {
            Route::get('/', [ConselhoController::class, 'agendaTemplates'])->name('index');
            Route::get('/create', [ConselhoController::class, 'criarTemplate'])->name('create');
            Route::post('/', [ConselhoController::class, 'salvarTemplate'])->name('store');
            Route::get('/{template}/edit', [ConselhoController::class, 'editarTemplate'])->name('edit');
            Route::put('/{template}', [ConselhoController::class, 'atualizarTemplate'])->name('update');
            Route::delete('/{template}', [ConselhoController::class, 'excluirTemplate'])->name('destroy');
            Route::post('/{template}/duplicar', [ConselhoController::class, 'duplicarTemplate'])->name('duplicate');
            Route::get('/{template}/usar', [ConselhoController::class, 'usarTemplate'])->name('use');
            Route::get('/history', [ConselhoController::class, 'templateHistory'])->name('history');
            Route::get('/export', [ConselhoController::class, 'exportarTemplates'])->name('export');
        });
        Route::get('/{conselho}', [ConselhoController::class, 'show'])->name('show');
        Route::get('/{conselho}/status', [ConselhoController::class, 'status'])->name('status');
        Route::get('/{conselho}/edit', [ConselhoController::class, 'edit'])->name('edit');
        Route::put('/{conselho}', [ConselhoController::class, 'update'])->name('update');
        Route::post('/{conselho}/iniciar', [ConselhoController::class, 'iniciar'])->name('iniciar');
        Route::post('/{conselho}/finalizar', [ConselhoController::class, 'finalizar'])->name('finalizar');
        Route::post('/{conselho}/cancelar', [ConselhoController::class, 'cancelar'])->name('cancelar');
        Route::prefix('{conselho}/attendance')->name('attendance.')->group(function () {
            Route::get('/', [ConselhoController::class, 'presenca'])->name('index');
            Route::post('/', [ConselhoController::class, 'atualizarPresenca'])->name('update');
            Route::get('/export', [ConselhoController::class, 'exportarPresenca'])->name('export');
            Route::get('/print', [ConselhoController::class, 'imprimirPresenca'])->name('print');
        });
        Route::prefix('{conselho}/agenda')->name('agenda.')->group(function () {
            Route::get('/', [ConselhoController::class, 'pautas'])->name('index');
            Route::get('/create', [ConselhoController::class, 'criarPauta'])->name('create');
            Route::post('/', [ConselhoController::class, 'adicionarPauta'])->name('store');
            Route::get('/{pauta}', [ConselhoController::class, 'mostrarPauta'])->name('show');
            Route::get('/{pauta}/edit', [ConselhoController::class, 'editarPauta'])->name('edit');
            Route::put('/{pauta}', [ConselhoController::class, 'atualizarPauta'])->name('update');
            Route::delete('/{pauta}', [ConselhoController::class, 'excluirPauta'])->name('destroy');
            Route::post('/{pauta}/discussao', [ConselhoController::class, 'iniciarDiscussao'])->name('discussao');
            Route::post('/{pauta}/finalizar', [ConselhoController::class, 'finalizarPauta'])->name('finalizar');
        });
        Route::prefix('{conselho}/voting')->name('voting.')->group(function () {
            Route::get('/', [ConselhoController::class, 'votacoes'])->name('index');
            Route::get('/create', [ConselhoController::class, 'criarVotacaoForm'])->name('create');
            Route::post('/', [ConselhoController::class, 'criarVotacao'])->name('store');
            Route::get('/{votacao}', [ConselhoController::class, 'mostrarVotacao'])->name('show');
            Route::post('/{votacao}/start', [ConselhoController::class, 'iniciarVotacao'])->name('start');
            Route::post('/{votacao}/finish', [ConselhoController::class, 'finalizarVotacao'])->name('finish');
            Route::post('/{votacao}/cancel', [ConselhoController::class, 'cancelarVotacao'])->name('cancel');
            Route::delete('/{votacao}', [ConselhoController::class, 'excluirVotacao'])->name('destroy');
            Route::post('/{votacao}/vote', [ConselhoController::class, 'votar'])->name('vote');
        });
        Route::get('/{conselho}/relatorio', [ConselhoController::class, 'relatorio'])->name('relatorio');
    });

    // Permissões
    Route::prefix('permissions')->name('permissions.')->middleware('permission:system.access')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/roles', [PermissionController::class, 'roles'])->name('roles');
        Route::post('/roles', [PermissionController::class, 'createRole'])->name('create-role');
        Route::get('/roles/{role}/edit', [PermissionController::class, 'editRole'])->name('edit-role');
        Route::put('/roles/{role}', [PermissionController::class, 'updateRole'])->name('update-role');
        Route::delete('/roles/{role}', [PermissionController::class, 'deleteRole'])->name('delete-role');
        Route::get('/permissions', [PermissionController::class, 'permissions'])->name('permissions');
        Route::post('/permissions', [PermissionController::class, 'createPermission'])->name('create-permission');
        Route::get('/permissions/{permission}/edit', [PermissionController::class, 'editPermission'])->name('edit-permission');
        Route::put('/permissions/{permission}', [PermissionController::class, 'updatePermission'])->name('update-permission');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'deletePermission'])->name('delete-permission');
        Route::get('/users', [PermissionController::class, 'users'])->name('users');
        Route::get('/users/{user}/edit', [PermissionController::class, 'editUser'])->name('edit-user');
        Route::put('/users/{user}', [PermissionController::class, 'updateUser'])->name('update-user');
        Route::get('/users/{user}/check', [PermissionController::class, 'checkUserPermissions'])->name('check-user');
        Route::post('/users/{user}/reset', [PermissionController::class, 'resetUserPermissions'])->name('reset-user');
        Route::post('/bulk-assign-roles', [PermissionController::class, 'bulkAssignRoles'])->name('bulk-assign-roles');
        Route::post('/bulk-assign-permissions', [PermissionController::class, 'bulkAssignPermissions'])->name('bulk-assign-permissions');
        Route::get('/reports', [PermissionController::class, 'reports'])->name('reports');
        Route::post('/import-default', [PermissionController::class, 'importDefaultPermissions'])->name('import-default');
    });

    // Intercessor (Sistema de Pedidos de Oração)
    Route::prefix('intercessor')->name('intercessor.')->middleware('permission:intercessor.access')->group(function () {
        Route::get('/', [IntercessorController::class, 'index'])->name('index');
        Route::get('/dashboard', [IntercessorController::class, 'dashboard'])->name('dashboard');
        Route::get('/{pedido}', [IntercessorController::class, 'show'])->name('show');
        Route::post('/{pedido}/registrar-intercessao', [IntercessorController::class, 'registrarIntercessao'])->name('registrar-intercessao');
        Route::put('/{pedido}/atualizar-status', [IntercessorController::class, 'atualizarStatus'])->name('atualizar-status');
        Route::get('/{pedido}/export', [IntercessorController::class, 'export'])->name('export');
        Route::delete('/{pedido}', [IntercessorController::class, 'destroy'])->name('destroy');
    });

    // Eventos
    Route::prefix('eventos')->name('eventos.')->middleware('permission:eventos.access')->group(function () {
        Route::get('/', [EventoController::class, 'index'])->name('index');
        Route::get('/create', [EventoController::class, 'create'])->name('create');
        Route::post('/', [EventoController::class, 'store'])->name('store');
        Route::get('/{evento}', [EventoController::class, 'show'])->name('show');
        Route::get('/{evento}/edit', [EventoController::class, 'edit'])->name('edit');
        Route::put('/{evento}', [EventoController::class, 'update'])->name('update');
        Route::delete('/{evento}', [EventoController::class, 'destroy'])->name('destroy');
        Route::post('/{evento}/toggle-status', [EventoController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{evento}/toggle-destaque', [EventoController::class, 'toggleDestaque'])->name('toggle-destaque');
        Route::get('/{evento}/inscricoes', [EventoController::class, 'inscricoes'])->name('inscricoes');
        Route::get('/{evento}/inscricoes/export', [EventoController::class, 'exportInscricoes'])->name('inscricoes.export');
        Route::get('/{evento}/exportar-inscricoes', [EventoController::class, 'exportInscricoes'])->name('exportar-inscricoes');
        Route::get('/{evento}/pagamentos', [EventoController::class, 'pagamentos'])->name('pagamentos');
        Route::get('/{evento}/exportar-pagamentos', [EventoController::class, 'exportPagamentos'])->name('exportar-pagamentos');
        Route::prefix('inscricoes')->name('inscricoes.')->group(function () {
            Route::post('/{inscricao}/confirmar', [EventoInscricaoController::class, 'confirmar'])->name('confirmar');
            Route::post('/{inscricao}/cancelar', [EventoInscricaoController::class, 'cancelar'])->name('cancelar');
            Route::post('/{inscricao}/presenca', [EventoInscricaoController::class, 'registrarPresenca'])->name('presenca');
            Route::post('/{inscricao}/ausencia', [EventoInscricaoController::class, 'registrarAusencia'])->name('ausencia');
            Route::post('/{inscricao}/certificado', [EventoInscricaoController::class, 'emitirCertificado'])->name('certificado');
            Route::delete('/{inscricao}', [EventoInscricaoController::class, 'destroy'])->name('destroy');
            Route::post('/{evento}/acao-lote', [EventoInscricaoController::class, 'acaoLote'])->name('acao-lote');
            Route::get('/{evento}/exportar-presenca', [EventoInscricaoController::class, 'exportarPresenca'])->name('exportar-presenca');
        });
        Route::prefix('pagamentos')->name('pagamentos.')->group(function () {
            Route::post('/{pagamento}/aprovar', [EventoInscricaoController::class, 'aprovarPagamento'])->name('aprovar');
            Route::post('/{pagamento}/rejeitar', [EventoInscricaoController::class, 'rejeitarPagamento'])->name('rejeitar');
            Route::post('/{pagamento}/cancelar', [EventoInscricaoController::class, 'cancelarPagamento'])->name('cancelar');
        });
    });

    // Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/manage', [ChatController::class, 'manage'])->name('manage');
        Route::get('/create', [ChatController::class, 'create'])->name('create');
        Route::post('/store', [ChatController::class, 'store'])->name('store');
        Route::get('/stats', [ChatController::class, 'stats'])->name('stats');
        Route::post('/bulk-clear', [ChatController::class, 'bulkClear'])->name('bulk-clear');
        Route::post('/bulk-clear-preview', [ChatController::class, 'bulkClearPreview'])->name('bulk-clear-preview');
        Route::get('/clear-stats', [ChatController::class, 'clearStats'])->name('clear-stats');
        Route::get('/test-clear-stats', [ChatController::class, 'testClearStats'])->name('test-clear-stats');
        Route::post('/backup', [ChatController::class, 'backupMessages'])->name('backup');
        Route::get('/backups', [ChatController::class, 'listBackups'])->name('backups');
        Route::get('/backups/{filename}', [ChatController::class, 'viewBackup'])->name('backups.view');
        Route::post('/backups/{filename}/restore', [ChatController::class, 'restoreBackup'])->name('backups.restore');
        Route::delete('/backups/{filename}', [ChatController::class, 'deleteBackup'])->name('backups.delete');
        Route::get('/backups/{filename}/download', [ChatController::class, 'downloadBackup'])->name('backups.download');
        Route::get('/{room}', [ChatController::class, 'show'])->name('show');
        Route::get('/{room}/edit', [ChatController::class, 'edit'])->name('edit');
        Route::put('/{room}', [ChatController::class, 'update'])->name('update');
        Route::delete('/{room}', [ChatController::class, 'destroy'])->name('destroy');
        Route::post('/{room}/send', [ChatController::class, 'sendMessage'])->name('send');
        Route::get('/{room}/participants', [ChatController::class, 'participants'])->name('participants');
        Route::post('/{room}/participants/add', [ChatController::class, 'addParticipant'])->name('participants.add');
        Route::delete('/{room}/participants/{participant}', [ChatController::class, 'removeParticipant'])->name('participants.remove');
        Route::patch('/{room}/participants/{participant}/toggle-mute', [ChatController::class, 'toggleMute'])->name('participants.toggle-mute');
        Route::delete('/{room}/messages/{message}', [ChatController::class, 'deleteMessage'])->name('messages.delete');
        Route::delete('/{room}/clear', [ChatController::class, 'clearChat'])->name('clear');
    });

    // EBD - ADMIN
    Route::prefix('ebd')->name('ebd.')->middleware('ebd.access')->group(function () {
        Route::get('/', function () { return redirect()->route('admin.ebd.turmas.index'); })->name('dashboard');
        Route::resource('turmas', AdminEbdTurmaController::class)->parameters(['turmas' => 'turma']);
        Route::resource('professores', AdminEbdProfessorController::class)->parameters(['professores' => 'professor']);
        Route::resource('alunos', AdminEbdAlunoController::class)->parameters(['alunos' => 'aluno']);
        Route::resource('licoes', AdminEbdLicaoController::class)->parameters(['licoes' => 'licao']);
        Route::resource('aulas', AdminEbdAulaController::class)->parameters(['aulas' => 'aula']);
        Route::resource('avaliacoes', AdminEbdAvaliacaoController::class)->parameters(['avaliacoes' => 'avaliacao']);
        Route::get('avaliacoes/{avaliacao}/relatorio', [AdminEbdAvaliacoController::class, 'relatorio'])->name('avaliacoes.relatorio');
        Route::resource('questoes', AdminEbdQuestaoController::class)->parameters(['questoes' => 'questao']);
        Route::post('questoes/importar', [AdminEbdQuestaoController::class, 'import'])->name('questoes.import');
        Route::post('questoes/exportar', [AdminEbdQuestaoController::class, 'export'])->name('questoes.export');
        Route::resource('certificados', AdminEbdCertificadoController::class)->parameters(['certificados' => 'certificado']);
        Route::post('certificados/gerar-automatico', [AdminEbdCertificadoController::class, 'gerarAutomatico'])->name('certificados.gerar-automatico');
        Route::get('certificados/{certificado}/download', [AdminEbdCertificadoController::class, 'download'])->name('certificados.download');
        Route::get('certificados/{certificado}/visualizar', [AdminEbdCertificadoController::class, 'visualizar'])->name('certificados.visualizar');
        Route::post('certificados/exportar', [AdminEbdCertificadoController::class, 'export'])->name('certificados.export');
        Route::get('relatorios', [AdminEbdRelatorioController::class, 'index'])->name('relatorios.index');
        Route::post('relatorios/exportar', [AdminEbdRelatorioController::class, 'exportar'])->name('relatorios.exportar');
        Route::post('relatorios/preview', [AdminEbdRelatorioController::class, 'preview'])->name('relatorios.preview');
        Route::resource('grupos-estudo', AdminEbdGruposEstudoController::class)->parameters(['grupos-estudo' => 'gruposEstudo']);
        Route::patch('grupos-estudo/{gruposEstudo}/toggle-status', [AdminEbdGruposEstudoController::class, 'toggleStatus'])->name('grupos-estudo.toggle-status');
        Route::post('grupos-estudo/{gruposEstudo}/adicionar-membro', [AdminEbdGruposEstudoController::class, 'adicionarMembro'])->name('grupos-estudo.adicionar-membro');
        Route::delete('grupos-estudo/{gruposEstudo}/remover-membro/{membro}', [AdminEbdGruposEstudoController::class, 'removerMembro'])->name('grupos-estudo.remover-membro');
        Route::get('grupos-estudo/{gruposEstudo}/relatorio', [AdminEbdGruposEstudoController::class, 'relatorio'])->name('grupos-estudo.relatorio');
        Route::get('ajax/alunos-por-turma', [AdminEbdGruposEstudoController::class, 'getAlunosPorTurma'])->name('ajax.alunos-por-turma');
        Route::get('ajax/todos-alunos', [AdminEbdGruposEstudoController::class, 'getTodosAlunos'])->name('ajax.todos-alunos');
        Route::resource('avaliacoes-grupo', AdminEbdAvaliacaoGrupoController::class)->parameters(['avaliacoes-grupo' => 'avaliacaoGrupo']);
        Route::post('avaliacoes-grupo/{avaliacaoGrupo}/iniciar', [AdminEbdAvaliacaoGrupoController::class, 'iniciar'])->name('avaliacoes-grupo.iniciar');
        Route::post('avaliacoes-grupo/{avaliacaoGrupo}/finalizar', [AdminEbdAvaliacaoGrupoController::class, 'finalizar'])->name('avaliacoes-grupo.finalizar');
        Route::get('avaliacoes-grupo-relatorio', [AdminEbdAvaliacaoGrupoController::class, 'relatorio'])->name('avaliacoes-grupo.relatorio');
        Route::get('ajax/grupos-por-avaliacao', [AdminEbdAvaliacaoGrupoController::class, 'getGruposPorAvaliacao'])->name('ajax.grupos-por-avaliacao');
        Route::prefix('quiz-biblico')->name('quiz-biblico.')->group(function () {
            Route::get('/', [AdminEbdQuizBiblicoController::class, 'index'])->name('index');
            Route::get('/create', [AdminEbdQuizBiblicoController::class, 'create'])->name('create');
            Route::post('/', [AdminEbdQuizBiblicoController::class, 'store'])->name('store');
            Route::get('/estatisticas', [AdminEbdQuizBiblicoController::class, 'estatisticas'])->name('estatisticas');
            Route::get('/estatisticas/exportar', [AdminEbdQuizBiblicoController::class, 'exportarEstatisticas'])->name('estatisticas.exportar');
            Route::get('/configuracoes', [AdminEbdQuizBiblicoController::class, 'configuracoes'])->name('configuracoes');
            Route::post('/configuracoes', [AdminEbdQuizBiblicoController::class, 'atualizarConfiguracoes'])->name('configuracoes.atualizar');
            Route::post('/testar-email', [AdminEbdQuizBiblicoController::class, 'testarEmail'])->name('testar-email');
            Route::post('/importar', [AdminEbdQuizBiblicoController::class, 'importar'])->name('importar');
            Route::get('/{pergunta}', [AdminEbdQuizBiblicoController::class, 'show'])->name('show');
            Route::get('/{pergunta}/edit', [AdminEbdQuizBiblicoController::class, 'edit'])->name('edit');
            Route::put('/{pergunta}', [AdminEbdQuizBiblicoController::class, 'update'])->name('update');
            Route::delete('/{pergunta}', [AdminEbdQuizBiblicoController::class, 'destroy'])->name('destroy');
        });
    });
});