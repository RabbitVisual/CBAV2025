<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\{
    BibleController,
    ChatController,
    DashboardController,
    DevocionalController,
    DonationController,
    EBD\DashboardController as EbdDashboardController,
    EBD\TurmaController as EbdTurmaController,
    EventoController,
    MessageController,
    MinistryController,
    PrayerRequestController,
    ProfileController,
    UserController
};

/*
|--------------------------------------------------------------------------
| Rotas da Área de Membros
|--------------------------------------------------------------------------
|
| Rotas para membros autenticados. Totalmente refatoradas para
| seguir as melhores práticas do Laravel.
|
*/

Route::middleware(['auth', 'role.redirect'])->prefix('member')->name('member.')->group(function () {

    // --- Dashboard e Perfil ---
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password.update');

    // --- Diretório de Membros ---
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

    // --- Módulos Principais ---
    Route::resource('prayer-requests', PrayerRequestController::class);
    Route::resource('messages', MessageController::class)->except(['edit', 'update']);
    Route::resource('devotionals', DevocionalController::class)->only(['index', 'show']);
    Route::resource('ministries', MinistryController::class)->only(['index', 'show']);
    Route::resource('events', EventoController::class)->only(['index', 'show']);
    Route::resource('donations', DonationController::class)->only(['index', 'show', 'create', 'store']);

    // --- Bíblia Online ---
    Route::prefix('bible')->name('bible.')->group(function () {
        Route::get('/', [BibleController::class, 'index'])->name('index');
        Route::post('/search', [BibleController::class, 'search'])->name('search');
        Route::get('/search-keyword', [BibleController::class, 'searchByKeyword'])->name('search-keyword');
        Route::get('/read-chapter', [BibleController::class, 'readChapter'])->name('read-chapter');
        Route::post('/change-version', [BibleController::class, 'changeVersion'])->name('change-version');
        Route::get('/favorites', [BibleController::class, 'favorites'])->name('favorites');
        Route::post('/favorites', [BibleController::class, 'addToFavorites'])->name('favorites.add');
        Route::delete('/favorites', [BibleController::class, 'removeFromFavorites'])->name('favorites.remove');
    });

    // --- Chat ---
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{room}', [ChatController::class, 'show'])->name('show');
        Route::post('/{room}/send', [ChatController::class, 'send'])->name('send');
    });

    // --- Escola Bíblica Dominical (EBD) ---
    Route::prefix('ebd')->name('ebd.')->middleware('can:ebd.access')->group(function () {
        Route::get('/', [EbdDashboardController::class, 'index'])->name('dashboard');
        Route::get('/turmas', [EbdTurmaController::class, 'index'])->name('turmas.index');
        Route::get('/turmas/{turma}', [EbdTurmaController::class, 'show'])->name('turmas.show');
        // Adicionar rotas para lições, aulas e quizzes conforme necessário
    });
});