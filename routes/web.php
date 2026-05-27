<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\OrderIndex;
use App\Livewire\Admin\ProductForm;
use App\Livewire\Admin\ProductIndex;
use App\Livewire\Admin\StockVelocity;
use App\Livewire\Admin\SuperDashboard;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Admin\UserForm;
use App\Livewire\Admin\UserIndex;
use App\Livewire\User\OrderHistory;
use App\Livewire\User\UserDashboard;
use App\Models\Product;
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

    $featuredProducts = Product::with('category')
        ->latest()
        ->take(3)
        ->get();

    return view('welcome', compact('featuredProducts'));
})->name('home');

Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment');
Route::get('/equipment/{product:sku}', [EquipmentController::class, 'show'])->name('products.show');

Route::prefix('/cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'add'])->name('add');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('remove');
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

Route::get('/benefits', function () {
    return view('benefits');
})->name('benefits');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact-us');
})->name('contact');

Route::prefix('/chat')->group(function () {
    Route::post('/send', [ChatbotController::class, 'handleChat']);
    Route::post('/action', [ChatbotController::class, 'handleAction']);
    Route::get('/history/{session_id}', [ChatbotController::class, 'getHistory']);
});


// Restricted Routes (Only for Logged-in Users)
Route::middleware(['auth'])->group(function () {

    // User Dashboard
    Route::get('/home', UserDashboard::class)
        ->middleware(['verified'])
        ->name('user.home');

    // User Order History
    Route::get('/my-orders', OrderHistory::class)->name('user.orders');

    // Checkout
    Route::prefix('/checkout')->name('checkout.')->group(function () {
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{order_number}', function ($order_number) {
            $order = \App\Models\Order::where('order_number', $order_number)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            return view('partials.checkout.success', compact('order'));
        })->name('success');
    });

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
    Route::get('/dashboard', SuperDashboard::class)
        ->middleware(['verified'])
        ->name('dashboard');

    // User Management CRUD
    Route::prefix('admin')->name('admin.')->group(function () {

        // Admin Product Management CRUD
        Route::prefix('/products')->name('products.')->group(function () {
            Route::get('/', ProductIndex::class)->name('index'); // Now admin.products.index
            Route::get('/create', ProductForm::class)->name('create');
            Route::get('/{product}/edit', ProductForm::class)->name('edit');
        });

        // Add this new route for Stock Velocity / Inventory
        Route::get('/inventory', StockVelocity::class)->name('inventory.index');

        // Admin Category Management CRUD
        Route::prefix('/categories')->name('categories.')->group(function () {
            Route::get('/', CategoryManager::class)->name('index');
        });

        // Admin Order Monitoring View
        Route::get('/orders', OrderIndex::class)->name('orders.index');

        // Admin Payment Transactions
        Route::get('/transactions', TransactionIndex::class)->name('transactions.index');

        // Admin User Management
        Route::prefix('/users')->name('users.')->group(function () {
            Route::get('/', UserIndex::class)->name('index');
            Route::get('/create', UserForm::class)->name('create');
            Route::get('/{user}/edit', UserForm::class)->name('edit');
        });

        // Admin AI Analytics Dashboard
        Route::get('/ai-analytics', \App\Livewire\Admin\AIAnalyticsDashboard::class)
            ->name('ai-analytics');
    });
});

require __DIR__ . '/settings.php';
