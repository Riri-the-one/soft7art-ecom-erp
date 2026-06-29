<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Le grand groupe de sécurité : il faut être connecté pour voir tout ça
Route::middleware('auth')->group(function () {
    
    // --- 1. ROUTES DU CATALOGUE ---
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    // Sous-groupe : Seuls le Gestionnaire de stock et le Directeur peuvent ajouter/modifier des produits
    Route::middleware('role:stock_manager,super_admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    });

    // --- 2. ROUTES DES COMMANDES ---
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Routes de création de commande (Agent et Admin) - DOIT être AVANT les routes dynamiques
    Route::middleware('role:agent,super_admin')->group(function () {
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    });
    
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('/orders/export/csv', [OrderController::class, 'exportCsv'])->name('orders.export');
    
    Route::middleware('role:agent')->group(function () {
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });

    // --- 3. ROUTES DES CLIENTS CRM ---
    Route::middleware('role:super_admin,agent')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });

    // --- 4. ROUTES DES ALERTES STOCK ---
    Route::middleware('role:stock_manager,super_admin')->group(function () {
        Route::get('/products/alerts', [ProductController::class, 'alerts'])->name('products.alerts');
    });

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
    });

    // --- 3. ROUTES DU PROFIL ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';