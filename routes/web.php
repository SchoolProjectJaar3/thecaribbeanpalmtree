<?php

use App\Http\Controllers\FormulierController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
