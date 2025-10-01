<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    AuthController,
    NotificacaoController,
    PagamentoController,
    GatewayController,
    PublicDonationController
};

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
    MessageController as MemberMessageController
};

use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    PeopleController,
    FinanceController,
    SystemController,
    DevocionalController,
    ConselhoController,
    ProfileController as AdminProfileController,
    PermissionController,
    EventoController,
    EventoInscricaoController,
    ChatController,
    CepController
};

use App\Http\Controllers\Admin\System\NotificationController as AdminSystemNotificationController;

use App\Http\Controllers\Admin\DocumentoBaixaController;
use App\Http\Controllers\Admin\DocumentoDeclaracaoAnualController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rotas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rota para servir arquivos de imagem
Route::get('membros/fotos/{filename}', function ($filename) {
    $path = storage_path('app/public/membros/fotos/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('filename', '.*');

// Rota para servir fotos de usuários
Route::get('users/fotos/{filename}', function ($filename) {
    $path = storage_path('app/public/users/fotos/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('filename', '.*');

// Páginas legais
Route::view('/termos-uso', 'public.termos-uso')->name('termos-uso');
Route::view('/politica-privacidade', 'public.politica-privacidade')->name('politica-privacidade');
Route::view('/politica-cookies', 'public.politica-cookies')->name('politica-cookies');

// Eventos Públicos
Route::prefix('eventos')->name('public.eventos.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\EventoController::class, 'index'])->name('index');
    Route::get('/{evento}', [App\Http\Controllers\Public\EventoController::class, 'show'])->name('show');
    Route::get('/{evento}/inscrever', [App\Http\Controllers\Public\EventoController::class, 'inscrever'])->name('inscrever');
    Route::post('/{evento}/inscrever', [App\Http\Controllers\Public\EventoController::class, 'processarInscricao'])->name('processar-inscricao');
    Route::get('/{evento}/pagamento/{inscricao}', [App\Http\Controllers\Public\EventoController::class, 'pagamento'])->name('pagamento');
    Route::post('/{evento}/pagamento/{inscricao}', [App\Http\Controllers\Public\EventoController::class, 'processarPagamento'])->name('processar-pagamento');
    Route::get('/inscricao/{inscricao}/confirmacao', [App\Http\Controllers\Public\EventoController::class, 'confirmacao'])->name('inscricao.confirmacao');
    Route::post('/verificar-inscricao', [App\Http\Controllers\Public\EventoController::class, 'verificarInscricao'])->name('verificar-inscricao');
});
Route::get('/creditos', function () {
    $dados = [
        'empresa' => [
            'nome' => 'Vertex Solutions',
            'slogan' => 'Transformando ideias em soluções digitais',
            'descricao' => 'Empresa especializada em desenvolvimento de software personalizado, focada em criar soluções inovadoras que atendem às necessidades específicas de cada cliente.',
            'especialidades' => [
                'Desenvolvimento Web',
                'Aplicações Mobile',
                'Sistemas Personalizados',
                'Consultoria em TI',
                'Integração de Sistemas',
                'Manutenção e Suporte'
            ]
        ],
        'ceo' => [
            'nome' => 'Reinan Rodrigues',
            'cargo' => 'CEO & Desenvolvedor Full Stack',
            'bio' => 'Desenvolvedor apaixonado por tecnologia com mais de 10 anos de experiência em desenvolvimento de software. Especialista em Laravel, React, Node.js e outras tecnologias modernas. Comprometido em entregar soluções de alta qualidade que realmente fazem a diferença para nossos clientes.',
            'email' => 'reinan@vertexsolutions.com.br',
            'whatsapp' => '+55 11 99999-9999',
            'linkedin' => 'https://linkedin.com/in/reinanrodrigues',
            'github' => 'https://github.com/reinanrodrigues',
            'habilidades' => [
                'Laravel',
                'React',
                'Node.js',
                'Vue.js',
                'MySQL',
                'PostgreSQL',
                'MongoDB',
                'Docker',
                'AWS',
                'Git',
                'REST APIs',
                'GraphQL'
            ],
            'projetos' => [
                'Sistema CBAV CRM Ministerial',
                'E-commerce Personalizado',
                'App de Gestão Empresarial',
                'Portal de Notícias',
                'Sistema de Vendas',
                'Dashboard Analytics'
            ]
        ],
        'sistema' => [
            'nome' => 'CBAV CRM Ministerial',
            'versao' => '2.0.0',
            'tecnologias' => [
                'Laravel 12',
                'PHP 8.2',
                'MySQL',
                'Tailwind CSS',
                'Alpine.js',
                'Livewire'
            ],
            'funcionalidades' => [
                'Gestão de Membros',
                'Controle Financeiro',
                'Sistema de Doações',
                'Gestão de Ministérios',
                'Relatórios Avançados',
                'Notificações'
            ],
            'recursos' => [
                'Interface Responsiva',
                'Multi-idioma',
                'Sistema de Permissões',
                'Backup Automático',
                'Logs de Atividade',
                'API REST'
            ]
        ]
    ];

    return view('creditos', compact('dados'));
})->name('creditos');

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas de doação pública (não logado)
Route::prefix('doacao')->name('doacao.')->middleware('check.payment.gateways')->group(function () {
    Route::get('/', [PublicDonationController::class, 'index'])->name('index');
    Route::post('/processar', [PublicDonationController::class, 'process'])->name('process');
    Route::get('/confirmacao/{transacao}', [PublicDonationController::class, 'confirmation'])->name('confirmation');
});

// Rotas protegidas por autenticação
Route::middleware(['auth', 'role.redirect'])->group(function () {

    // ========================================
    // ÁREA DE MEMBROS (Todos os usuários)
    // ========================================
    Route::prefix('member')->name('member.')->group(function () {
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

            // Rotas de gateways específicas para membros
            Route::get('/stripe', [MemberDonationController::class, 'showStripe'])->name('stripe.show');
            Route::get('/mercadopago', [MemberDonationController::class, 'showMercadoPago'])->name('mercadopago.show');
            Route::get('/pix', [MemberDonationController::class, 'showPix'])->name('pix.show');
            Route::post('/pix/verificar/{transacaoId}', [MemberDonationController::class, 'verificarPagamentoPix'])->name('pix.verificar');

            // Novas rotas para detalhes e comprovantes
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
            Route::get('/', [App\Http\Controllers\Member\PedidoOracaoController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Member\PedidoOracaoController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\Member\PedidoOracaoController::class, 'store'])->name('store');
            Route::get('/{pedido}', [App\Http\Controllers\Member\PedidoOracaoController::class, 'show'])->name('show');
            Route::get('/{pedido}/edit', [App\Http\Controllers\Member\PedidoOracaoController::class, 'edit'])->name('edit');
            Route::put('/{pedido}', [App\Http\Controllers\Member\PedidoOracaoController::class, 'update'])->name('update');
            Route::delete('/{pedido}', [App\Http\Controllers\Member\PedidoOracaoController::class, 'destroy'])->name('destroy');
            Route::post('/{pedido}/marcar-atendido', [App\Http\Controllers\Member\PedidoOracaoController::class, 'marcarAtendido'])->name('marcar-atendido');
            Route::post('/{pedido}/participar-intercessao', [App\Http\Controllers\Member\PedidoOracaoController::class, 'participarIntercessao'])->name('participar-intercessao');
            Route::get('/answered', [App\Http\Controllers\Member\PedidoOracaoController::class, 'answered'])->name('answered');
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
    });

    // ========================================
    // ÁREA ADMINISTRATIVA
    // ========================================
    Route::prefix('admin')->name('admin.')->middleware('admin.access')->group(function () {

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
            Route::get('/', [PeopleController::class, 'index'])->name('index');
            Route::get('/export', [PeopleController::class, 'export'])->name('export');

            // Busca de CEP
            Route::get('/buscar-cep/{cep}', [PeopleController::class, 'buscarCep'])->name('buscar-cep');

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

            // Membros
            Route::prefix('members')->name('members.')->middleware('permission:members.access')->group(function () {
                Route::get('/', [PeopleController::class, 'members'])->name('index');
                Route::get('/create', [PeopleController::class, 'createMember'])->name('create');
                Route::post('/', [PeopleController::class, 'storeMember'])->name('store');
                Route::get('/import', [PeopleController::class, 'importMembers'])->name('import');
                Route::post('/import', [PeopleController::class, 'processImportMembers'])->name('import.process');
                Route::get('/export', [PeopleController::class, 'exportMembers'])->name('export');
                Route::get('/{membro}', [PeopleController::class, 'showMember'])->name('show');
                Route::get('/{membro}/edit', [PeopleController::class, 'editMember'])->name('edit');
                Route::put('/{membro}', [PeopleController::class, 'updateMember'])->name('update');
                Route::delete('/{membro}', [PeopleController::class, 'deleteMember'])->name('delete');
                Route::delete('/{membro}', [PeopleController::class, 'deleteMember'])->name('destroy'); // Alias para compatibilidade

                Route::get('/{membro}/ficha', [PeopleController::class, 'memberCard'])->name('ficha');
                Route::get('/{membro}/ficha/preview', [PeopleController::class, 'memberCardPreview'])->name('ficha.preview');
                Route::get('/{membro}/ficha/print', [PeopleController::class, 'memberCardPrint'])->name('ficha.print');
            });

            // Usuários
            Route::prefix('users')->name('users.')->middleware('permission:users.access')->group(function () {
                Route::get('/', [PeopleController::class, 'users'])->name('index');
                Route::get('/create', [PeopleController::class, 'createUser'])->name('create');
                Route::post('/', [PeopleController::class, 'storeUser'])->name('store');
                Route::get('/{user}', [PeopleController::class, 'showUser'])->name('show');
                Route::get('/{user}/edit', [PeopleController::class, 'editUser'])->name('edit');
                Route::put('/{user}', [PeopleController::class, 'updateUser'])->name('update');
                Route::delete('/{user}', [PeopleController::class, 'deleteUser'])->name('delete');
                Route::post('/{user}/activate', [PeopleController::class, 'activateUser'])->name('activate');
                Route::post('/{user}/deactivate', [PeopleController::class, 'deactivateUser'])->name('deactivate');
                Route::get('/export', [PeopleController::class, 'exportUsers'])->name('export');
                Route::post('/bulk-action', [PeopleController::class, 'bulkActionUsers'])->name('bulk-action');
            });

            // Ministérios
            Route::prefix('ministries')->name('ministries.')->middleware('permission:ministries.access')->group(function () {
                Route::get('/', [PeopleController::class, 'ministries'])->name('index');
                Route::get('/create', [PeopleController::class, 'createMinistry'])->name('create');
                Route::post('/', [PeopleController::class, 'storeMinistry'])->name('store');
                Route::get('/{ministerio}', [PeopleController::class, 'showMinistry'])->name('show');
                Route::get('/{ministerio}/edit', [PeopleController::class, 'editMinistry'])->name('edit');
                Route::put('/{ministerio}', [PeopleController::class, 'updateMinistry'])->name('update');
                Route::delete('/{ministerio}', [PeopleController::class, 'deleteMinistry'])->name('delete');
                Route::delete('/{ministerio}', [PeopleController::class, 'deleteMinistry'])->name('destroy'); // Alias para compatibilidade
                Route::get('/export', [PeopleController::class, 'exportMinistries'])->name('export');
                Route::get('/{ministerio}/export', [PeopleController::class, 'exportMinistry'])->name('ministry.export');
            });

            // Departamentos
            Route::prefix('departments')->name('departments.')->middleware('permission:departments.access')->group(function () {
                Route::get('/', [PeopleController::class, 'departments'])->name('index');
                Route::get('/create', [PeopleController::class, 'createDepartment'])->name('create');
                Route::post('/', [PeopleController::class, 'storeDepartment'])->name('store');
                Route::get('/{departamento}', [PeopleController::class, 'showDepartment'])->name('show');
                Route::get('/{departamento}/edit', [PeopleController::class, 'editDepartment'])->name('edit');
                Route::put('/{departamento}', [PeopleController::class, 'updateDepartment'])->name('update');
                Route::delete('/{departamento}', [PeopleController::class, 'deleteDepartment'])->name('delete');
                Route::get('/{departamento}/export', [PeopleController::class, 'exportDepartment'])->name('export');
            });

            // Cargos
            Route::prefix('cargos')->name('cargos.')->middleware('permission:departments.access')->group(function () {
                Route::get('/', [PeopleController::class, 'cargos'])->name('index');
                Route::get('/create', [PeopleController::class, 'createCargo'])->name('create');
                Route::post('/', [PeopleController::class, 'storeCargo'])->name('store');
                Route::get('/{cargo}/edit', [PeopleController::class, 'editCargo'])->name('edit');
                Route::put('/{cargo}', [PeopleController::class, 'updateCargo'])->name('update');
                Route::delete('/{cargo}', [PeopleController::class, 'deleteCargo'])->name('delete');
            });

            // Aniversariantes
            Route::prefix('birthdays')->name('birthdays.')->middleware('permission:members.access')->group(function () {
                Route::get('/', [PeopleController::class, 'birthdays'])->name('index');
                Route::get('/upcoming', [PeopleController::class, 'upcomingBirthdays'])->name('upcoming');
                Route::get('/export', [PeopleController::class, 'exportBirthdays'])->name('export');
                Route::get('/upcoming/export', [PeopleController::class, 'exportUpcomingBirthdays'])->name('upcoming.export');
            });

            // Relatórios
            Route::prefix('reports')->name('reports.')->middleware('permission:reports.access')->group(function () {
                Route::get('/', [PeopleController::class, 'reports'])->name('index');
                Route::get('/export', [PeopleController::class, 'exportReports'])->name('export');
                Route::get('/export-all', [PeopleController::class, 'exportAllReports'])->name('export-all');
                Route::get('/members', [PeopleController::class, 'reportsMembers'])->name('members');
                Route::get('/ministries', [PeopleController::class, 'reportsMinistries'])->name('ministries');
                Route::get('/birthdays', [PeopleController::class, 'reportsBirthdays'])->name('birthdays');
                Route::get('/statistics', [PeopleController::class, 'reportsStatistics'])->name('statistics');
                Route::get('/quick/{tipo}', [PeopleController::class, 'reportsQuick'])->name('quick');
                Route::get('/complete', [PeopleController::class, 'reportsComplete'])->name('complete');
            });
        });

        // Gestão Financeira
        Route::prefix('finance')->name('finance.')->middleware('permission:finance.access')->group(function () {
            Route::get('/', [FinanceController::class, 'index'])->name('index');
            Route::get('/dashboard', [FinanceController::class, 'index'])->name('dashboard');

            // Transações
            Route::prefix('transactions')->name('transactions.')->middleware('permission:transactions.access')->group(function () {
                Route::get('/', [FinanceController::class, 'transactions'])->name('index');
                Route::get('/create', [FinanceController::class, 'createTransaction'])->name('create');
                Route::post('/', [FinanceController::class, 'storeTransaction'])->name('store');
                Route::get('/export', [FinanceController::class, 'exportTransactions'])->name('export');
                Route::get('/{transacao}', [FinanceController::class, 'showTransaction'])->name('show');
                Route::get('/{transacao}/edit', [FinanceController::class, 'editTransaction'])->name('edit');
                Route::put('/{transacao}', [FinanceController::class, 'updateTransaction'])->name('update');
                Route::delete('/{transacao}', [FinanceController::class, 'deleteTransaction'])->name('delete');
                // Rota GET alternativa para exclusão (apenas para super admin e tesoureiro)
                Route::get('/{transacao}/delete', [FinanceController::class, 'deleteTransactionGet'])->name('delete.get')->middleware('permission:transactions.delete');
                // Rota para exclusão em lote
                Route::post('/bulk-delete', [FinanceController::class, 'bulkDeleteTransactions'])->name('bulk-delete')->middleware('permission:transactions.delete');
                // Rota para exportar comprovante de transação específica
                Route::get('/{transacao}/comprovante', [FinanceController::class, 'exportTransactionComprovante'])->name('comprovante');
            });

            // Campanhas
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

            // Relatórios
            Route::prefix('reports')->name('reports.')->middleware('permission:reports.access')->group(function () {
                Route::get('/', [FinanceController::class, 'reports'])->name('index');
                Route::get('/export', [FinanceController::class, 'exportReports'])->name('export');
            });

            // Configurações
            Route::prefix('settings')->name('settings.')->middleware('permission:settings.access')->group(function () {
                Route::get('/', [FinanceController::class, 'settings'])->name('index');
                Route::post('/', [FinanceController::class, 'updateSettings'])->name('update');
            });

            // Documentos de Baixa
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
        });

        // Gestão do Sistema
        Route::prefix('system')->name('system.')->middleware('permission:system.access')->group(function () {
            Route::get('/', [SystemController::class, 'index'])->name('index');
            Route::get('/dashboard', [SystemController::class, 'index'])->name('dashboard');

            // Configurações
            Route::prefix('settings')->name('settings.')->middleware('permission:settings.access')->group(function () {
                Route::get('/', [SystemController::class, 'settings'])->name('index');
                Route::put('/', [SystemController::class, 'updateSettings'])->name('update');
                Route::post('/test-email', [SystemController::class, 'testEmail'])->name('test-email');
            });

            // Configurações da Homepage
            Route::prefix('home-config')->name('home-config.')->middleware('permission:settings.access')->group(function () {
                Route::get('/', [SystemController::class, 'homeConfig'])->name('index');
                Route::put('/', [SystemController::class, 'updateHomeConfig'])->name('update');
                Route::post('/reset', [SystemController::class, 'resetHomeConfig'])->name('reset');
                Route::get('/test', [SystemController::class, 'test'])->name('test');
            });

            // Rota de teste sem middleware

            // Notificações
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

            // Logs
            Route::prefix('logs')->name('logs.')->group(function () {
                Route::get('/', [SystemController::class, 'logs'])->name('index');
                Route::post('/clear', [SystemController::class, 'clearLogs'])->name('clear');
                Route::post('/clear-old', [SystemController::class, 'clearOldLogs'])->name('clear-old');
                Route::get('/export', [SystemController::class, 'exportLogs'])->name('export');
                Route::get('/show', [SystemController::class, 'showLog'])->name('show');
            });

            // Manutenção
            Route::prefix('maintenance')->name('maintenance.')->middleware('permission:system.access')->group(function () {
                Route::get('/', [SystemController::class, 'maintenance'])->name('index');
                Route::post('/backup', [SystemController::class, 'backup'])->name('backup');
                Route::post('/cache', [SystemController::class, 'clearCache'])->name('cache');
                Route::post('/enable', [SystemController::class, 'enableMaintenance'])->name('enable');
                Route::post('/disable', [SystemController::class, 'disableMaintenance'])->name('disable');
            });

            // Cache
            Route::prefix('cache')->name('cache.')->group(function () {
                Route::post('/clear', [SystemController::class, 'clearCache'])->name('clear');
                Route::post('/test', [SystemController::class, 'testCache'])->name('test');
            });

            // Backup
            Route::prefix('backup')->name('backup.')->group(function () {
            Route::post('/run', [SystemController::class, 'runBackup'])->name('run');
            Route::get('/list', [SystemController::class, 'listBackups'])->name('list');
            Route::post('/test', [SystemController::class, 'testBackup'])->name('test');
            Route::post('/clean', [SystemController::class, 'cleanOldBackups'])->name('clean');
        });

            // Rotas adicionais do sistema
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

            // Bíblia offline
            Route::post('/buscar-versiculo-offline', [DevocionalController::class, 'buscarVersiculoOffline'])->name('buscar-versiculo-offline');
            Route::post('/buscar-versiculo-aleatorio', [DevocionalController::class, 'buscarVersiculoAleatorio'])->name('buscar-versiculo-aleatorio');
            Route::post('/buscar-por-palavra-chave', [DevocionalController::class, 'buscarPorPalavraChave'])->name('buscar-por-palavra-chave');

            // Rotas com parâmetros (devem vir por último)
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

            // Rotas de configurações (devem vir antes das rotas com parâmetros)
            Route::get('/settings', [ConselhoController::class, 'settings'])->name('settings');
            Route::put('/settings', [ConselhoController::class, 'updateSettings'])->name('settings.update');
            Route::get('/voting/history', [ConselhoController::class, 'votingHistory'])->name('voting.history');
            Route::get('/export', [ConselhoController::class, 'exportar'])->name('export');

            // Templates de Pauta (devem vir antes das rotas com parâmetros)
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

            // Rotas com parâmetros (devem vir por último)
            Route::get('/{conselho}', [ConselhoController::class, 'show'])->name('show');
            Route::get('/{conselho}/status', [ConselhoController::class, 'status'])->name('status');
            Route::get('/{conselho}/edit', [ConselhoController::class, 'edit'])->name('edit');
            Route::put('/{conselho}', [ConselhoController::class, 'update'])->name('update');
            Route::post('/{conselho}/iniciar', [ConselhoController::class, 'iniciar'])->name('iniciar');
            Route::post('/{conselho}/finalizar', [ConselhoController::class, 'finalizar'])->name('finalizar');
            Route::post('/{conselho}/cancelar', [ConselhoController::class, 'cancelar'])->name('cancelar');

            // Presença
            Route::prefix('{conselho}/attendance')->name('attendance.')->group(function () {
                Route::get('/', [ConselhoController::class, 'presenca'])->name('index');
                Route::post('/', [ConselhoController::class, 'atualizarPresenca'])->name('update');
                Route::get('/export', [ConselhoController::class, 'exportarPresenca'])->name('export');
                Route::get('/print', [ConselhoController::class, 'imprimirPresenca'])->name('print');
            });

            // Agenda
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

            // Votações
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

            // Relatórios
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
            Route::get('/', [App\Http\Controllers\Admin\IntercessorController::class, 'index'])->name('index');
            Route::get('/dashboard', [App\Http\Controllers\Admin\IntercessorController::class, 'dashboard'])->name('dashboard');
            Route::get('/{pedido}', [App\Http\Controllers\Admin\IntercessorController::class, 'show'])->name('show');
            Route::post('/{pedido}/registrar-intercessao', [App\Http\Controllers\Admin\IntercessorController::class, 'registrarIntercessao'])->name('registrar-intercessao');
            Route::put('/{pedido}/atualizar-status', [App\Http\Controllers\Admin\IntercessorController::class, 'atualizarStatus'])->name('atualizar-status');
            Route::get('/{pedido}/export', [App\Http\Controllers\Admin\IntercessorController::class, 'export'])->name('export');
            Route::delete('/{pedido}', [App\Http\Controllers\Admin\IntercessorController::class, 'destroy'])->name('destroy');
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

            // Inscrições
            Route::get('/{evento}/inscricoes', [EventoController::class, 'inscricoes'])->name('inscricoes');
            Route::get('/{evento}/inscricoes/export', [EventoController::class, 'exportInscricoes'])->name('inscricoes.export');
            Route::get('/{evento}/exportar-inscricoes', [EventoController::class, 'exportInscricoes'])->name('exportar-inscricoes');

            // Pagamentos
            Route::get('/{evento}/pagamentos', [EventoController::class, 'pagamentos'])->name('pagamentos');
            Route::get('/{evento}/exportar-pagamentos', [EventoController::class, 'exportPagamentos'])->name('exportar-pagamentos');

            // Gerenciamento de inscrições
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

            // Gerenciamento de pagamentos
            Route::prefix('pagamentos')->name('pagamentos.')->group(function () {
                Route::post('/{pagamento}/aprovar', [EventoInscricaoController::class, 'aprovarPagamento'])->name('aprovar');
                Route::post('/{pagamento}/rejeitar', [EventoInscricaoController::class, 'rejeitarPagamento'])->name('rejeitar');
                Route::post('/{pagamento}/cancelar', [EventoInscricaoController::class, 'cancelarPagamento'])->name('cancelar');
            });
        });

        // Rotas de administração do chat
        Route::prefix('chat')->name('chat.')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::get('/manage', [ChatController::class, 'manage'])->name('manage');
            Route::get('/create', [ChatController::class, 'create'])->name('create');
            Route::post('/store', [ChatController::class, 'store'])->name('store');
            Route::get('/stats', [ChatController::class, 'stats'])->name('stats');

            // Rotas estáticas que devem vir antes da rota dinâmica /{room}
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

            // Rotas por sala
            Route::get('/{room}', [ChatController::class, 'show'])->name('show');
            Route::get('/{room}/edit', [ChatController::class, 'edit'])->name('edit');
            Route::put('/{room}', [ChatController::class, 'update'])->name('update');
            Route::delete('/{room}', [ChatController::class, 'destroy'])->name('destroy');
            Route::post('/{room}/send', [ChatController::class, 'sendMessage'])->name('send');

            // Gerenciamento de participantes
            Route::get('/{room}/participants', [ChatController::class, 'participants'])->name('participants');
            Route::post('/{room}/participants/add', [ChatController::class, 'addParticipant'])->name('participants.add');
            Route::delete('/{room}/participants/{participant}', [ChatController::class, 'removeParticipant'])->name('participants.remove');
            Route::patch('/{room}/participants/{participant}/toggle-mute', [ChatController::class, 'toggleMute'])->name('participants.toggle-mute');

            // Ações em mensagens da sala
            Route::delete('/{room}/messages/{message}', [ChatController::class, 'deleteMessage'])->name('messages.delete');
            Route::delete('/{room}/clear', [ChatController::class, 'clearChat'])->name('clear');
        });
    });
});

// ========================================
// ROTAS DE PAGAMENTO (Públicas)
// ========================================
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/pix', [PagamentoController::class, 'showPix'])->name('pix.show');
    Route::post('/stripe', [PagamentoController::class, 'processarStripe'])->name('stripe');
    Route::post('/mercadopago', [PagamentoController::class, 'processarMercadoPago'])->name('mercadopago');
    Route::post('/pix', [PagamentoController::class, 'gerarPix'])->name('pix');
    Route::post('/verificar/{transacao}', [PagamentoController::class, 'verificarPagamento'])->name('verificar');
    Route::post('/webhook/stripe', [PagamentoController::class, 'webhookStripe'])->name('webhook.stripe');
    Route::post('/webhook/mercadopago', [PagamentoController::class, 'webhookMercadoPago'])->name('webhook.mercadopago');
});

// ========================================
// ROTAS DE GATEWAY (Públicas)
// ========================================
Route::prefix('gateway')->name('gateway.')->group(function () {
    Route::get('/stripe/{transacao}', [GatewayController::class, 'stripe'])->name('stripe');
    Route::get('/mercadopago/{transacao}', [GatewayController::class, 'mercadopago'])->name('mercadopago');
    Route::get('/verify/{transacao}', [GatewayController::class, 'verifyPayment'])->name('verify');
});

// Rotas EBD - ADMIN
Route::prefix('admin/ebd')->name('admin.ebd.')->middleware(['auth', 'admin.access', 'ebd.access'])->group(function () {
    // Dashboard EBD Admin
    Route::get('/', function () {
        return redirect()->route('admin.ebd.turmas.index');
    })->name('dashboard');

    // Turmas
    Route::resource('turmas', App\Http\Controllers\Admin\EbdTurmaController::class)->parameters(['turmas' => 'turma']);
    // Professores
    Route::resource('professores', App\Http\Controllers\Admin\EbdProfessorController::class)->parameters(['professores' => 'professor']);
    // Alunos
    Route::resource('alunos', App\Http\Controllers\Admin\EbdAlunoController::class)->parameters(['alunos' => 'aluno']);
    // Lições
    Route::resource('licoes', App\Http\Controllers\Admin\EbdLicaoController::class)->parameters(['licoes' => 'licao']);
    // Aulas
    Route::resource('aulas', App\Http\Controllers\Admin\EbdAulaController::class)->parameters(['aulas' => 'aula']);
    // Avaliações
    Route::resource('avaliacoes', App\Http\Controllers\Admin\EbdAvaliacaoController::class)->parameters(['avaliacoes' => 'avaliacao']);
    Route::get('avaliacoes/{avaliacao}/relatorio', [App\Http\Controllers\Admin\EbdAvaliacaoController::class, 'relatorio'])->name('avaliacoes.relatorio');

    // Questões
    Route::resource('questoes', App\Http\Controllers\Admin\EbdQuestaoController::class)->parameters(['questoes' => 'questao']);
    Route::post('questoes/importar', [App\Http\Controllers\Admin\EbdQuestaoController::class, 'import'])->name('questoes.import');
    Route::post('questoes/exportar', [App\Http\Controllers\Admin\EbdQuestaoController::class, 'export'])->name('questoes.export');

    // Certificados
    Route::resource('certificados', App\Http\Controllers\Admin\EbdCertificadoController::class)->parameters(['certificados' => 'certificado']);
    Route::post('certificados/gerar-automatico', [App\Http\Controllers\Admin\EbdCertificadoController::class, 'gerarAutomatico'])->name('certificados.gerar-automatico');
    Route::get('certificados/{certificado}/download', [App\Http\Controllers\Admin\EbdCertificadoController::class, 'download'])->name('certificados.download');
    Route::get('certificados/{certificado}/visualizar', [App\Http\Controllers\Admin\EbdCertificadoController::class, 'visualizar'])->name('certificados.visualizar');
    Route::post('certificados/exportar', [App\Http\Controllers\Admin\EbdCertificadoController::class, 'export'])->name('certificados.export');

    // Relatórios
    Route::get('relatorios', [App\Http\Controllers\Admin\EbdRelatorioController::class, 'index'])->name('relatorios.index');
    Route::post('relatorios/exportar', [App\Http\Controllers\Admin\EbdRelatorioController::class, 'exportar'])->name('relatorios.exportar');
    Route::post('relatorios/preview', [App\Http\Controllers\Admin\EbdRelatorioController::class, 'preview'])->name('relatorios.preview');

    // Grupos de Estudo
    Route::resource('grupos-estudo', App\Http\Controllers\Admin\EBD\GruposEstudoController::class)->parameters(['grupos-estudo' => 'gruposEstudo']);
    Route::patch('grupos-estudo/{gruposEstudo}/toggle-status', [App\Http\Controllers\Admin\EBD\GruposEstudoController::class, 'toggleStatus'])->name('grupos-estudo.toggle-status');
    Route::post('grupos-estudo/{gruposEstudo}/adicionar-membro', [App\Http\Controllers\Admin\EBD\GruposEstudoController::class, 'adicionarMembro'])->name('grupos-estudo.adicionar-membro');
    Route::delete('grupos-estudo/{gruposEstudo}/remover-membro/{membro}', [App\Http\Controllers\Admin\EBD\GruposEstudoController::class, 'removerMembro'])->name('grupos-estudo.remover-membro');
    Route::get('grupos-estudo/{gruposEstudo}/relatorio', [App\Http\Controllers\Admin\EBD\GruposEstudoController::class, 'relatorio'])->name('grupos-estudo.relatorio');
    Route::get('ajax/alunos-por-turma', [App\Http\Controllers\Admin\EBD\GruposEstudoController::class, 'getAlunosPorTurma'])->name('ajax.alunos-por-turma');
    Route::get('ajax/todos-alunos', [App\Http\Controllers\Admin\EBD\GruposEstudoController::class, 'getTodosAlunos'])->name('ajax.todos-alunos');

    // Avaliações em Grupo
    Route::resource('avaliacoes-grupo', App\Http\Controllers\Admin\EbdAvaliacaoGrupoController::class)->parameters(['avaliacoes-grupo' => 'avaliacaoGrupo']);
    Route::post('avaliacoes-grupo/{avaliacaoGrupo}/iniciar', [App\Http\Controllers\Admin\EbdAvaliacaoGrupoController::class, 'iniciar'])->name('avaliacoes-grupo.iniciar');
    Route::post('avaliacoes-grupo/{avaliacaoGrupo}/finalizar', [App\Http\Controllers\Admin\EbdAvaliacaoGrupoController::class, 'finalizar'])->name('avaliacoes-grupo.finalizar');
    Route::get('avaliacoes-grupo-relatorio', [App\Http\Controllers\Admin\EbdAvaliacaoGrupoController::class, 'relatorio'])->name('avaliacoes-grupo.relatorio');
    Route::get('ajax/grupos-por-avaliacao', [App\Http\Controllers\Admin\EbdAvaliacaoGrupoController::class, 'getGruposPorAvaliacao'])->name('ajax.grupos-por-avaliacao');

    // Quiz Bíblico
    Route::prefix('quiz-biblico')->name('quiz-biblico.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'store'])->name('store');

        // Rotas específicas devem vir ANTES das rotas com parâmetros
        Route::get('/estatisticas', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'estatisticas'])->name('estatisticas');
        Route::get('/estatisticas/exportar', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'exportarEstatisticas'])->name('estatisticas.exportar');
        Route::get('/configuracoes', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'configuracoes'])->name('configuracoes');
        Route::post('/configuracoes', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'atualizarConfiguracoes'])->name('configuracoes.atualizar');
        Route::post('/testar-email', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'testarEmail'])->name('testar-email');
        Route::post('/importar', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'importar'])->name('importar');

        // Rotas com parâmetros devem vir DEPOIS das rotas específicas
        Route::get('/{pergunta}', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'show'])->name('show');
        Route::get('/{pergunta}/edit', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'edit'])->name('edit');
        Route::put('/{pergunta}', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'update'])->name('update');
        Route::delete('/{pergunta}', [App\Http\Controllers\Admin\EbdQuizBiblicoController::class, 'destroy'])->name('destroy');
    });
});

// Rotas EBD - MEMBRO
Route::prefix('member/ebd')->name('member.ebd.')->middleware(['auth', 'can:member-access'])->group(function () {
    // Dashboard do aluno
    Route::get('/', [App\Http\Controllers\Member\EbdAlunoDashboardController::class, 'index'])->name('dashboard');

    // Visualizar turmas
    Route::get('turmas', [App\Http\Controllers\Member\EbdTurmaController::class, 'index'])->name('turmas.index');
    Route::get('turmas/{turma}', [App\Http\Controllers\Member\EbdTurmaController::class, 'show'])->name('turmas.show');

    // Visualizar lições e aulas
    Route::get('licoes', [App\Http\Controllers\Member\EbdLicaoController::class, 'index'])->name('licoes.index');
    Route::get('licoes/{licao}', [App\Http\Controllers\Member\EbdLicaoController::class, 'show'])->name('licoes.show');

    Route::get('aulas', [App\Http\Controllers\Member\EbdAulaController::class, 'index'])->name('aulas.index');
    Route::get('aulas/{aula}', [App\Http\Controllers\Member\EbdAulaController::class, 'show'])->name('aulas.show');

    // Quiz/Avaliação
    Route::get('avaliacoes/{avaliacao}/quiz', [App\Http\Controllers\Member\EbdQuizController::class, 'show'])->name('quiz.show');
    Route::post('avaliacoes/{avaliacao}/quiz', [App\Http\Controllers\Member\EbdQuizController::class, 'responder'])->name('quiz.responder');

    // Grupos de Estudo
    Route::get('grupos', [App\Http\Controllers\Member\EBD\GruposController::class, 'index'])->name('grupos.index');
    Route::get('grupos/{grupo}', [App\Http\Controllers\Member\EBD\GruposController::class, 'show'])->name('grupos.show');
    Route::post('grupos/{grupo}/entrar', [App\Http\Controllers\Member\EBD\GruposController::class, 'entrar'])->name('grupos.entrar');
    Route::delete('grupos/{grupo}/sair', [App\Http\Controllers\Member\EBD\GruposController::class, 'sair'])->name('grupos.sair');

    // Avaliações em Grupo
    Route::get('avaliacoes/{avaliacao}/participar', [App\Http\Controllers\Member\EBD\GruposController::class, 'avaliar'])->name('avaliacoes.participar');
    Route::get('avaliacoes/{avaliacao}/questao/{questaoOrdem}', [App\Http\Controllers\Member\EBD\GruposController::class, 'avaliar'])->name('avaliacoes.questao');
    Route::post('avaliacoes/{avaliacao}/questao/{questao}/responder', [App\Http\Controllers\Member\EBD\GruposController::class, 'responder'])->name('avaliacoes.responder');
    Route::post('avaliacoes/{avaliacao}/questao/{questao}/contribuir', [App\Http\Controllers\Member\EBD\GruposController::class, 'contribuir'])->name('avaliacoes.contribuir');
    Route::post('avaliacoes/{avaliacao}/finalizar', [App\Http\Controllers\Member\EBD\GruposController::class, 'finalizar'])->name('avaliacoes.finalizar');

    // Certificados
    Route::get('certificados', [App\Http\Controllers\Member\EbdCertificadoController::class, 'index'])->name('certificados.index');
    Route::get('certificados/{certificado}', [App\Http\Controllers\Member\EbdCertificadoController::class, 'show'])->name('certificados.show');
    Route::get('certificados/{certificado}/download', [App\Http\Controllers\Member\EbdCertificadoController::class, 'download'])->name('certificados.download');

    // Quiz Bíblico
    Route::prefix('quiz-biblico')->name('quiz-biblico.')->group(function () {
        Route::get('/', [App\Http\Controllers\Member\EbdQuizBiblicoController::class, 'index'])->name('index');
        Route::post('/iniciar', [App\Http\Controllers\Member\EbdQuizBiblicoController::class, 'iniciar'])->name('iniciar');
        Route::get('/jogar/{sessao}', [App\Http\Controllers\Member\EbdQuizBiblicoController::class, 'jogar'])->name('jogar');
        Route::post('/responder/{sessao}', [App\Http\Controllers\Member\EbdQuizBiblicoController::class, 'responder'])->name('responder');
        Route::get('/resultado/{sessao}', [App\Http\Controllers\Member\EbdQuizBiblicoController::class, 'resultado'])->name('resultado');
        Route::get('/historico', [App\Http\Controllers\Member\EbdQuizBiblicoController::class, 'historico'])->name('historico');
    });
});

// Rotas para Documentos de Declaração Anual
Route::prefix('admin/finance/documentos-declaracao-anual')->name('admin.finance.documentos-declaracao-anual.')->group(function () {
    Route::get('/', [DocumentoDeclaracaoAnualController::class, 'index'])->name('index');
    Route::get('/create', [DocumentoDeclaracaoAnualController::class, 'create'])->name('create');
    Route::post('/', [DocumentoDeclaracaoAnualController::class, 'store'])->name('store');
    // Exportação (deve vir antes das rotas com parâmetros)
    Route::get('/export', [DocumentoDeclaracaoAnualController::class, 'export'])->name('export');

    // API para estatísticas
    Route::get('/estatisticas/por-igreja', [DocumentoDeclaracaoAnualController::class, 'estatisticasPorIgreja'])->name('estatisticas.por-igreja');

    // Rotas com parâmetros (devem vir depois das rotas específicas)
    Route::get('/{documento}', [DocumentoDeclaracaoAnualController::class, 'show'])->name('show');
    Route::get('/{documento}/edit', [DocumentoDeclaracaoAnualController::class, 'edit'])->name('edit');
    Route::put('/{documento}', [DocumentoDeclaracaoAnualController::class, 'update'])->name('update');
    Route::delete('/{documento}', [DocumentoDeclaracaoAnualController::class, 'destroy'])->name('destroy');

    // Ações específicas
    Route::get('/{documento}/pdf', [DocumentoDeclaracaoAnualController::class, 'pdf'])->name('pdf');
    Route::get('/{documento}/codigo-barras', [DocumentoDeclaracaoAnualController::class, 'codigoBarras'])->name('codigo-barras');
    Route::get('/{documento}/qr-code', [DocumentoDeclaracaoAnualController::class, 'qrCode'])->name('qr-code');
    Route::get('/{documento}/calcular-multa-juros', [DocumentoDeclaracaoAnualController::class, 'calcularMultaJuros'])->name('calcular-multa-juros');
    Route::post('/{documento}/validar', [DocumentoDeclaracaoAnualController::class, 'validar'])->name('validar');
});

// Rotas públicas de validação de documentos
Route::prefix('validacao')->group(function () {
    Route::get('/documento/{hash}', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarDocumento'])->name('validacao.documento');
    Route::get('/declaracao-anual/{hash}', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarDeclaracaoAnual'])->name('validacao.declaracao-anual');
    Route::get('/baixa/{hash}', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarBaixa'])->name('validacao.baixa');
    Route::post('/verificar', [App\Http\Controllers\Public\DocumentoValidationController::class, 'verificarDocumento'])->name('validacao.verificar');
});

// Rotas públicas para validação de documentos
Route::get('/public/validacao/baixa', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarBaixa'])->name('public.validacao.baixa');
Route::get('/public/validacao/declaracao-anual', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarDeclaracaoAnual'])->name('public.validacao.declaracao-anual');
Route::get('/public/validacao/comprovante', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarComprovante'])->name('public.validacao.comprovante');

// Chat - Membros (adicionado ao grupo principal de membros)
Route::prefix('member/chat')->name('member.chat.')->middleware(['auth', 'role.redirect'])->group(function () {
    Route::get('/', [App\Http\Controllers\Member\ChatController::class, 'index'])->name('index');
    Route::get('/stats', [App\Http\Controllers\Member\ChatController::class, 'getChatStats'])->name('stats');
    Route::get('/{room}', [App\Http\Controllers\Member\ChatController::class, 'show'])->name('show');
    Route::post('/{room}/join', [App\Http\Controllers\Member\ChatController::class, 'join'])->name('join');
    Route::post('/{room}/leave', [App\Http\Controllers\Member\ChatController::class, 'leave'])->name('leave');
    Route::post('/{room}/send', [App\Http\Controllers\Member\ChatController::class, 'send'])->name('send');
    Route::post('/{room}/read', [App\Http\Controllers\Member\ChatController::class, 'read'])->name('read');
    Route::get('/{room}/messages', [App\Http\Controllers\Member\ChatController::class, 'messages'])->name('messages');
});
