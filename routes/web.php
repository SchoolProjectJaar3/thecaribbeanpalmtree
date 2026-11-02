<?php

use App\Http\Controllers\Beheer\DashboardController;
use App\Http\Controllers\Beheer\TekstenController;
use App\Http\Controllers\Beheer\WoningController;
use App\Http\Controllers\FormulierController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

// Publieke pagina's (PVA hoofdstuk 5 en 6)
Route::view('/het-huis', 'pages.het-huis')->name('het-huis');
Route::view('/fotos', 'pages.fotos')->name('fotos');
Route::view('/beschikbaarheid', 'pages.beschikbaarheid')->name('beschikbaarheid');
Route::view('/tarieven', 'pages.tarieven')->name('tarieven');
Route::view('/omgeving', 'pages.omgeving')->name('omgeving');
Route::view('/reviews', 'pages.reviews')->name('reviews');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/over-de-eigenaar', 'pages.over-de-eigenaar')->name('over-de-eigenaar');
Route::view('/praktische-informatie', 'pages.praktische-informatie')->name('praktische-informatie');
Route::view('/privacy-voorwaarden', 'pages.privacy-voorwaarden')->name('privacy-voorwaarden');

Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact', [FormulierController::class, 'contact'])->middleware('throttle:6,1')->name('contact.verstuur');

Route::view('/reserveren', 'pages.reserveren')->name('reserveren');
Route::post('/reserveren', [FormulierController::class, 'reserveren'])->middleware('throttle:6,1')->name('reserveren.verstuur');

// Beheeromgeving voor de eigenaar
Route::middleware(['auth', 'active'])->prefix('beheer')->name('beheer.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::view('/reserveringen', 'beheer.reserveringen')->name('reserveringen');
    Route::view('/beschikbaarheid', 'beheer.beschikbaarheid')->name('beschikbaarheid');
    Route::view('/tarieven', 'beheer.tarieven')->name('tarieven');
    Route::view('/fotos', 'beheer.fotos')->name('fotos');

    Route::get('/teksten', [TekstenController::class, 'index'])->name('teksten');
    Route::get('/teksten/algemeen', [TekstenController::class, 'editAlgemeen'])->name('teksten.algemeen');
    Route::put('/teksten/algemeen', [TekstenController::class, 'updateAlgemeen'])->name('teksten.algemeen.update');
    Route::get('/teksten/{page:key}', [TekstenController::class, 'edit'])->name('teksten.edit');
    Route::put('/teksten/{page:key}', [TekstenController::class, 'update'])->name('teksten.update');

    Route::get('/woning', [WoningController::class, 'edit'])->name('woning');
    Route::put('/woning', [WoningController::class, 'update'])->name('woning.update');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
