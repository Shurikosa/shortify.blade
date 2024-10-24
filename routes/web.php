<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing_page');
});

Route::get('/dashboard', [LinkController::class, 'index'])
->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/dashboard/links/store', [LinkController::class, 'store'])->name('links.store');
    Route::post('/dashboard/links/update/{id}', [LinkController::class, 'update'])->name('links.update');
    Route::delete('/dashboard/links/destroy/{id}', [LinkController::class, 'destroy'])->name('links.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/{short_link}', [LinkController::class, 'redirect'])->where('short_link', '[A-Za-z0-9]+')->name('links.redirect');
