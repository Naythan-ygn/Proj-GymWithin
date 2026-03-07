<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\ProductForm;
use App\Livewire\Admin\ProductIndex;
use App\Livewire\Admin\UserForm;
use App\Livewire\Admin\UserIndex;
use App\Livewire\User\UserDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    if (Auth::check()) {
        // Accessing the 'role' property from the authenticated user model
        return Auth::user()->role === 'admin'
            ? redirect()->route('dashboard')
            : redirect()->route('user.home');
    }

    return view('welcome');
})->name('home');

Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment');

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

    // User Dashboard
    Route::get('/home', UserDashboard::class)
        ->middleware(['verified'])
        ->name('user.home');

    // Login User Profile Settings
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
    // User Management CRUD
    Route::prefix('admin')->name('admin.')->group(function () {

        // Fix: Added the dot after 'products' in the name
        Route::prefix('/products')->name('products.')->group(function () {
            Route::get('/', ProductIndex::class)->name('index'); // Now admin.products.index
            Route::get('/create', ProductForm::class)->name('create');
            Route::get('/{product}/edit', ProductForm::class)->name('edit');
        });

        Route::prefix('/categories')->name('categories.')->group(function () {
            Route::get('/', CategoryManager::class)->name('index');
        });
        
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', UserIndex::class)->name('index');
            Route::get('/create', UserForm::class)->name('create');
            Route::get('/{user}/edit', UserForm::class)->name('edit');
        });
    });
});

require __DIR__ . '/settings.php';
