<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [App\Http\Controllers\Auth\AuthController::class, 'index'])->name('login');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/priority-analysis', [App\Http\Controllers\ClassificationABC\PriorityAnalysisController::class, 'index'])->name('priority-analysis');

    Route::prefix('product')->name('product.')->group(function (): void {
        Route::get('/barcode-scanner', [App\Http\Controllers\ManageProduct\ProductController::class, 'indexBarcodeScanner'])->name('barcode-scanner');

        Route::get('/index', [App\Http\Controllers\ManageProduct\ProductController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ManageProduct\ProductController::class, 'create'])->name('create');
        Route::get('/{product}', [App\Http\Controllers\ManageProduct\ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [App\Http\Controllers\ManageProduct\ProductController::class, 'edit'])->name('edit');
    });

    Route::prefix('production')->name('production.')->group(function (): void {
        Route::get('/report', [App\Http\Controllers\ManageProduction\ProductionController::class, 'report'])->name('report');

        Route::get('/index', [App\Http\Controllers\ManageProduction\ProductionController::class, 'index'])->name('index');
        Route::get('/{production}', [App\Http\Controllers\ManageProduction\ProductionController::class, 'show'])->name('show');
        Route::get('/{production}/edit', [App\Http\Controllers\ManageProduction\ProductionController::class, 'edit'])->name('edit');

        Route::prefix('request')->name('request.')->group(function (): void {
            Route::get('/index', [App\Http\Controllers\ManageProduction\ProductionRequestController::class, 'index'])->name('index');
            Route::get('/{production}/edit', [App\Http\Controllers\ManageProduction\ProductionRequestController::class, 'edit'])->name('edit');
        });
    });

    Route::prefix('sales')->name('sales.')->group(function (): void {
        Route::get('/report', [App\Http\Controllers\ManageSales\SalesController::class, 'report'])->name('report');

        Route::get('/index', [App\Http\Controllers\ManageSales\SalesController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ManageSales\SalesController::class, 'create'])->name('create');
        Route::get('/{sales}', [App\Http\Controllers\ManageSales\SalesController::class, 'show'])->name('show');

        Route::prefix('request_product')->name('request.product.')->group(function (): void {
            Route::get('/index', [App\Http\Controllers\ManageSales\SaleProductRequestController::class, 'index'])->name('index');
            Route::get('/manage_stock', [App\Http\Controllers\ManageSales\SaleProductRequestController::class, 'manage_stock'])->name('manage-stock');
            Route::get('/create', [App\Http\Controllers\ManageSales\SaleProductRequestController::class, 'create'])->name('create');
            Route::get('/{product_request}', [App\Http\Controllers\ManageSales\SaleProductRequestController::class, 'show'])->name('show');
            Route::get('/{product_request}/edit', [App\Http\Controllers\ManageSales\SaleProductRequestController::class, 'edit'])->name('edit');
        });
    });

    Route::prefix('inventory')->name('inventory.')->group(function (): void {
        Route::get('/report', [App\Http\Controllers\ManageInventory\InventoryController::class, 'report'])->name('report');

        Route::get('/in/index', [App\Http\Controllers\ManageInventory\InventoryController::class, 'indexIn'])->name('in.index');
        Route::get('/out/index', [App\Http\Controllers\ManageInventory\InventoryController::class, 'indexOut'])->name('out.index');

        Route::prefix('request')->name('request.')->group(function (): void {
            Route::get('/index', [App\Http\Controllers\ManageInventory\InventoryProductionRequestController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\ManageInventory\InventoryProductionRequestController::class, 'create'])->name('create');
            Route::get('/{production}', [App\Http\Controllers\ManageInventory\InventoryProductionRequestController::class, 'show'])->name('show');
            Route::get('/{production}/edit', [App\Http\Controllers\ManageInventory\InventoryProductionRequestController::class, 'edit'])->name('edit');
            Route::get('/{production}/edit/status', [App\Http\Controllers\ManageInventory\InventoryProductionRequestController::class, 'status'])->name('edit-status');
        });

        Route::prefix('request_product')->name('request.product.')->group(function (): void {
            Route::get('/index', [App\Http\Controllers\ManageInventory\InventoryProductRequestController::class, 'index'])->name('index');
            Route::get('/{product_request}', [App\Http\Controllers\ManageInventory\InventoryProductRequestController::class, 'show'])->name('show');
            Route::get('/{product_request}/edit', [App\Http\Controllers\ManageInventory\InventoryProductRequestController::class, 'edit'])->name('edit');
        });
    });

    Route::prefix('user')->name('user.')->group(function (): void {
        Route::get('/index', [App\Http\Controllers\ManageAccess\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\ManageAccess\UserController::class, 'create'])->name('create');
        Route::get('/{user}', [App\Http\Controllers\ManageAccess\UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [App\Http\Controllers\ManageAccess\UserController::class, 'edit'])->name('edit');
        Route::get('/{user}/edit/profile', [App\Http\Controllers\ManageAccess\UserController::class, 'profile'])->name('edit.profile');
    });

    Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
});
