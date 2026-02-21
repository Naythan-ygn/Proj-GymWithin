<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\UserForm;
use App\Livewire\Admin\UserIndex;
use Illuminate\Support\Facades\Route;

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


// Restricted Routes (Only for Logged-in Users)
Route::middleware(['auth'])->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get('/settings', [ProfileController::class, 'editSettings'])->name('user.settings');
        Route::patch('/settings', [ProfileController::class, 'updateSettings'])->name('user.settings.update');
        Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('user.password.update');
        Route::delete('/settings', [ProfileController::class, 'destroy'])->name('user.settings.destroy');
    });
});

// Restricted Routes (Only for Logged-in Admins)
Route::middleware(['auth', 'admin'])->group(function () {

    // Admin Dashboard
    Route::view('dashboard', 'admin.dashboard')
        ->middleware(['verified'])
        ->name('dashboard');

    // User Management CRUD
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', UserIndex::class)->name('users.index');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{user}/edit', UserForm::class)->name('users.edit');
    });
});
require __DIR__ . '/settings.php';
