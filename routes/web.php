<?php

// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\PrintingController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\QCController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\KanbanController;
use App\Http\Controllers\CompletedOrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnalyticsController;


Auth::routes(['register' => false]);

// Redirect root ke login jika belum auth, atau ke dashboard jika sudah auth
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::resource('users', UserController::class);
    Route::put('/users/{id}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])
        ->name('analytics.index');
    Route::get('/reports', [AnalyticsController::class, 'index'])->name('reports.index');

    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');
    
    // Purchase Orders - Admin only
    Route::middleware(['permission:purchase_order'])->group(function () {
        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::get('purchase-orders/{id}/print-spk', [PurchaseOrderController::class, 'printSpk'])->name('purchase-orders.print-spk');
        Route::post('purchase-orders/{id}/move', [PurchaseOrderController::class, 'moveToStage'])->name('purchase-orders.move');
        Route::delete('purchase-orders/images/{id}', [PurchaseOrderController::class, 'deleteImage'])->name('purchase-orders.delete-image');
    });

    // Desain
    Route::middleware(['permission:desain'])->prefix('desain')->name('desain.')->group(function () {
        Route::get('/', [DesainController::class, 'index'])->name('index');
        Route::post('/{id}/update-status', [DesainController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/move', [DesainController::class, 'moveToStage'])->name('move');
        Route::delete('/images/{id}', [DesainController::class, 'deleteImage'])->name('delete-image');
    });

    // Printing
    Route::middleware(['permission:printing'])->prefix('printing')->name('printing.')->group(function () {
        Route::get('/', [PrintingController::class, 'index'])->name('index');
        Route::post('/{id}/update-status', [PrintingController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/move', [PrintingController::class, 'moveToStage'])->name('move');
    });

    // Press
    Route::middleware(['permission:press'])->prefix('press')->name('press.')->group(function () {
        Route::get('/', [PressController::class, 'index'])->name('index');
        Route::post('/{id}/update-status', [PressController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/move', [PressController::class, 'moveToStage'])->name('move');
    });

    // QC
    Route::middleware(['permission:qc'])->prefix('qc')->name('qc.')->group(function () {
        Route::get('/', [QCController::class, 'index'])->name('index');
        Route::post('/{id}/update-status', [QCController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/move', [QCController::class, 'moveToStage'])->name('move');
    });

    // Pengiriman
    Route::middleware(['permission:pengiriman'])->prefix('pengiriman')->name('pengiriman.')->group(function () {
        Route::get('/', [PengirimanController::class, 'index'])->name('index');
        Route::post('/{id}/update-status', [PengirimanController::class, 'updateStatus'])->name('update-status');
        Route::post('/{id}/move', [PengirimanController::class, 'moveToStage'])->name('move');
    });

    // Kanban
    Route::middleware(['permission:kanban'])->prefix('kanban')->name('kanban.')->group(function () {
        Route::get('/', [KanbanController::class, 'index'])->name('index');
        Route::get('/{id}', [KanbanController::class, 'show'])->name('show');
    });

    Route::prefix('completed-orders')->name('completed-orders.')->group(function () {
        Route::get('/', [CompletedOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [CompletedOrderController::class, 'show'])->name('show');
        Route::get('/{id}/pdf', [CompletedOrderController::class, 'exportPdf'])->name('pdf');
    });
    
});
