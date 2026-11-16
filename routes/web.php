<?php

use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TextController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

// Public pages (project plan chapters 5 and 6)
Route::view('/the-house', 'pages.property')->name('property');
Route::view('/photos', 'pages.photos')->name('photos');
Route::view('/availability', 'pages.availability')->name('availability');
Route::view('/rates', 'pages.rates')->name('rates');
Route::view('/surroundings', 'pages.surroundings')->name('surroundings');
Route::view('/reviews', 'pages.reviews')->name('reviews');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/about-the-owner', 'pages.owner')->name('owner');
Route::view('/practical-information', 'pages.practical-information')->name('practical-information');
Route::view('/privacy-terms', 'pages.privacy')->name('privacy');

Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact', [FormController::class, 'contact'])->middleware('throttle:6,1')->name('contact.send');

Route::view('/book', 'pages.booking')->name('booking');
Route::post('/book', [FormController::class, 'booking'])->middleware('throttle:6,1')->name('booking.send');

// Admin area for the owner
Route::middleware(['auth', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::view('/reservations', 'admin.reservations')->name('reservations');
    Route::view('/availability', 'admin.availability')->name('availability');
    Route::view('/rates', 'admin.rates')->name('rates');
    Route::view('/photos', 'admin.photos')->name('photos');

    Route::get('/texts', [TextController::class, 'index'])->name('texts');
    Route::get('/texts/general', [TextController::class, 'editGeneral'])->name('texts.general');
    Route::put('/texts/general', [TextController::class, 'updateGeneral'])->name('texts.general.update');
    Route::get('/texts/{page:key}', [TextController::class, 'edit'])->name('texts.edit');
    Route::put('/texts/{page:key}', [TextController::class, 'update'])->name('texts.update');

    Route::get('/accommodation', [AccommodationController::class, 'edit'])->name('accommodation');
    Route::put('/accommodation', [AccommodationController::class, 'update'])->name('accommodation.update');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
