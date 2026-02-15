<?php

use App\Http\Controllers\EquipmentController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\UserIndex;
use App\Livewire\Admin\UserForm;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get(
    '/equipment',
    [EquipmentController::class, 'index']
)
    ->name('equipment');

Route::get('/benefits', function () {
    return view('benefits');
})->name('benefits');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact-us');
})->name('contact');

Route::middleware(['auth'])->group(function () {
    // admin dashboard
    Route::view('dashboard', 'admin.dashboard')
        ->middleware(['verified'])
        ->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        // This defines admin.users.index
        Route::get('/users', UserIndex::class)->name('users.index');

        // This defines admin.users.create
        Route::get('/users/create', UserForm::class)->name('users.create');

        // This defines admin.users.edit
        Route::get('/users/{user}/edit', UserForm::class)->name('users.edit');
    });
});

require __DIR__ . '/settings.php';
