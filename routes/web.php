<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route::view('/', 'welcome')->name('home');
Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/applications', \App\Livewire\Applications\Index::class)->name('applications.index');
    Route::get('/applications/{applicationId}', \App\Livewire\Applications\Show::class)->name('applications.show');
});

Route::get('/documents/{document}/download', function (\App\Models\ApplicationDocument $document) {
    abort_unless($document->application->user_id === Auth::id(), 403);

    return response()->download(
        Storage::disk('local')->path($document->path),
        $document->original_filename
    );
})->middleware(['auth', 'verified'])->name('documents.download');

require __DIR__.'/settings.php';