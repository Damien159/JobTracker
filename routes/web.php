<?php

use Illuminate\Support\Facades\Route;

// Route::view('/', 'welcome')->name('home');
Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/applications', \App\Livewire\Applications\Index::class)->name('applications.index');
    Route::get('/applications/{applicationId}', \App\Livewire\Applications\Show::class)->name('applications.show');
});

require __DIR__.'/settings.php';
