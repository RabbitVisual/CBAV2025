<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicDonationController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Public\EventoController as PublicEventoController;
use App\Http\Controllers\Public\DocumentoValidationController as PublicDocumentoValidationController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
|
| Aqui são registradas as rotas que não exigem autenticação.
|
*/

// Página inicial
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rota para servir arquivos de imagem de membros
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

// Créditos
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

// Rotas de doação pública (não logado)
Route::prefix('doacao')->name('doacao.')->middleware('check.payment.gateways')->group(function () {
    Route::get('/', [PublicDonationController::class, 'index'])->name('index');
    Route::post('/processar', [PublicDonationController::class, 'process'])->name('process');
    Route::get('/confirmacao/{transacao}', [PublicDonationController::class, 'confirmation'])->name('confirmation');
});

// Rotas de Pagamento
Route::prefix('payment')->name('payment.')->group(function () {
    Route::get('/pix', [PagamentoController::class, 'showPix'])->name('pix.show');
    Route::post('/stripe', [PagamentoController::class, 'processarStripe'])->name('stripe');
    Route::post('/mercadopago', [PagamentoController::class, 'processarMercadoPago'])->name('mercadopago');
    Route::post('/pix', [PagamentoController::class, 'gerarPix'])->name('pix');
    Route::post('/verificar/{transacao}', [PagamentoController::class, 'verificarPagamento'])->name('verificar');
    Route::post('/webhook/stripe', [PagamentoController::class, 'webhookStripe'])->name('webhook.stripe');
    Route::post('/webhook/mercadopago', [PagamentoController::class, 'webhookMercadoPago'])->name('webhook.mercadopago');
});

// Rotas de Gateway
Route::prefix('gateway')->name('gateway.')->group(function () {
    Route::get('/stripe/{transacao}', [GatewayController::class, 'stripe'])->name('stripe');
    Route::get('/mercadopago/{transacao}', [GatewayController::class, 'mercadopago'])->name('mercadopago');
    Route::get('/verify/{transacao}', [GatewayController::class, 'verifyPayment'])->name('verify');
});

// Rotas públicas de validação de documentos
Route::prefix('validacao')->group(function () {
    Route::get('/documento/{hash}', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarDocumento'])->name('validacao.documento');
    Route::get('/declaracao-anual/{hash}', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarDeclaracaoAnual'])->name('validacao.declaracao-anual');
    Route::get('/baixa/{hash}', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarBaixa'])->name('validacao.baixa');
    Route::post('/verificar', [App\Http\Controllers\Public\DocumentoValidationController::class, 'verificarDocumento'])->name('validacao.verificar');
});

Route::get('/public/validacao/baixa', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarBaixa'])->name('public.validacao.baixa');
Route::get('/public/validacao/declaracao-anual', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarDeclaracaoAnual'])->name('public.validacao.declaracao-anual');
Route::get('/public/validacao/comprovante', [App\Http\Controllers\Public\DocumentoValidationController::class, 'validarComprovante'])->name('public.validacao.comprovante');