<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatisticController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:super_admin,agent,stock_manager'])->name('dashboard');

// Page de suivi publique (non-authentifiée) : le lien complet contient un token
// unique et imprévisible qui fait office d'autorisation, donc pas besoin de login.
Route::get('/suivi/{order}/{token}', [OrderController::class, 'track'])->name('orders.track');

// Le grand groupe de sécurité : il faut être connecté pour voir tout ça
Route::middleware(['auth', 'role:super_admin,agent,stock_manager'])->group(function () {

    // --- 1. ROUTES DU CATALOGUE ---
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Sous-groupe : Seuls le Gestionnaire de stock et le Directeur peuvent ajouter/modifier des produits
    Route::middleware('role:stock_manager,super_admin')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::get('/products/{product}/barcode', [ProductController::class, 'barcode'])->name('products.barcode');
    });

    // --- 2. ROUTES DES COMMANDES ---
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/kanban', [OrderController::class, 'kanban'])->name('orders.kanban');

    Route::middleware('role:agent,super_admin')->group(function () {
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        // Vérification AJAX de doublon avant l'enregistrement d'une commande.
        Route::post('/orders/check-duplicate', [OrderController::class, 'checkDuplicate'])->name('orders.checkDuplicate');
    });

    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    // Routes for export and invoice - restricted to super_admin and agent only
    Route::middleware('role:super_admin,agent')->group(function () {
        Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
        Route::get('/orders/{order}/label', [OrderController::class, 'label'])->name('orders.label');
        Route::get('/orders/export/csv', [OrderController::class, 'exportCsv'])->name('orders.export');
        Route::get('/orders/export/logistics', [OrderController::class, 'exportLogisticsCsv'])->name('orders.export.logistics');
    });

    // Le contrôle de rôle pour updateStatus est désormais géré par OrderPolicy::updateStatus()
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

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
