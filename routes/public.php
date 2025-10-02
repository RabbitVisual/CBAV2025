<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PublicDonationController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\Public\EventoController as PublicEventoController;
use App\Http\Controllers\Public\DocumentoValidationController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
|
| Rotas que não exigem autenticação. Totalmente refatoradas para
| seguir as melhores práticas do Laravel, permitindo cache de rotas.
|
*/

// --- Rotas Principais ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/creditos', [HomeController::class, 'credits'])->name('credits');

// --- Rotas de Arquivos Protegidos ---
Route::get('/files/{directory}/{filename}', [FileController::class, 'serveImage'])
    ->where('filename', '.*')->name('file.serve');

// --- Páginas Legais (Termos, Privacidade) ---
Route::view('/termos-de-uso', 'public.legal.termos-uso')->name('legal.terms');
Route::view('/politica-de-privacidade', 'public.legal.politica-privacidade')->name('legal.privacy');
Route::view('/politica-de-cookies', 'public.legal.politica-cookies')->name('legal.cookies');

// --- Eventos Públicos ---
Route::prefix('eventos')->name('public.events.')->group(function () {
    Route::get('/', [PublicEventoController::class, 'index'])->name('index');
    Route::get('/{evento}', [PublicEventoController::class, 'show'])->name('show');
    Route::get('/{evento}/inscrever', [PublicEventoController::class, 'inscrever'])->name('register');
    Route::post('/{evento}/inscrever', [PublicEventoController::class, 'processarInscricao'])->name('process-registration');
    Route::get('/inscricao/{inscricao}/confirmacao', [PublicEventoController::class, 'confirmacao'])->name('registration.confirmation');
    Route::post('/verificar-inscricao', [PublicEventoController::class, 'verificarInscricao'])->name('check-registration');
});

// --- Doações Públicas ---
Route::prefix('doacao')->name('donation.')->middleware('check.payment.gateways')->group(function () {
    Route::get('/', [PublicDonationController::class, 'index'])->name('index');
    Route::post('/processar', [PublicDonationController::class, 'process'])->name('process');
    Route::get('/confirmacao/{transacao}', [PublicDonationController::class, 'confirmation'])->name('confirmation');
});

// --- Rotas de Gateway de Pagamento e Webhooks ---
Route::prefix('payment')->name('payment.')->group(function () {
    // Redirecionamento para gateways
    Route::get('/gateway/stripe/{transacao}', [GatewayController::class, 'stripe'])->name('gateway.stripe');
    Route::get('/gateway/mercadopago/{transacao}', [GatewayController::class, 'mercadopago'])->name('gateway.mercadopago');

    // Verificação de status
    Route::get('/verify/{transacao}', [GatewayController::class, 'verifyPayment'])->name('gateway.verify');

    // Processamento de pagamentos (PIX, etc.)
    Route::post('/pix', [PagamentoController::class, 'gerarPix'])->name('pix.generate');

    // Webhooks para confirmação de pagamento
    Route::post('/webhook/stripe', [PagamentoController::class, 'webhookStripe'])->name('webhook.stripe');
    Route::post('/webhook/mercadopago', [PagamentoController::class, 'webhookMercadoPago'])->name('webhook.mercadopago');
});

// --- Validação de Documentos ---
Route::prefix('validation')->name('validation.')->group(function () {
    Route::get('/documento/{hash}', [DocumentoValidationController::class, 'validarDocumento'])->name('document');
    Route::get('/declaracao-anual/{hash}', [DocumentoValidationController::class, 'validarDeclaracaoAnual'])->name('annual-declaration');
    Route::get('/baixa/{hash}', [DocumentoValidationController::class, 'validarBaixa'])->name('baixa');
    Route::post('/verificar', [DocumentoValidationController::class, 'verificarDocumento'])->name('verify');
    Route::get('/comprovante/{hash}', [DocumentoValidationController::class, 'validarComprovante'])->name('receipt');
});