<?php

use App\Livewire\Applications\Index;
use App\Livewire\Applications\Show;
use App\Livewire\Dashboard;
use App\Models\ApplicationDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

// Route::view('/', 'welcome')->name('home');
Route::redirect('/', '/login')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('/applications', Index::class)->name('applications.index');
    Route::get('/applications/{applicationId}', Show::class)->name('applications.show');
});

Route::get('/documents/{document}/download', function (ApplicationDocument $document) {
    abort_unless($document->application->user_id === Auth::id(), 403);

    return response()->download(
        Storage::disk('local')->path($document->path),
        $document->original_filename
    );
})->middleware(['auth', 'verified'])->name('documents.download');

require __DIR__.'/settings.php';
