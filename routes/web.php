<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;

use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\PrintingController;
use App\Http\Controllers\PressController;
use App\Http\Controllers\QCController;
use App\Http\Controllers\PengirimanController;

use App\Http\Controllers\KanbanController;
use App\Http\Controllers\CompletedOrderController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

Auth::routes(['register' => false]);

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Authenticated + Permission Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard (Permission Required)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard')
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Analytics (Permission Required)
    |--------------------------------------------------------------------------
    */
    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->middleware('permission:analytics')
        ->name('analytics.index');

    Route::get('/reports', [AnalyticsController::class, 'index'])
        ->middleware('permission:analytics')
        ->name('reports.index');


    /*
    |--------------------------------------------------------------------------
    | Home Redirect
    |--------------------------------------------------------------------------
    */
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');


    /*
    |--------------------------------------------------------------------------
    | ✅ User Management (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:user_management'])->group(function () {

        Route::resource('users', UserController::class);

        Route::put('/users/{id}/toggle', [UserController::class, 'toggle'])
            ->name('users.toggle');

        Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.resetPassword');
    });


    /*
    |--------------------------------------------------------------------------
    | ✅ Role Management (ADMIN ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:role_management'])->group(function () {
        Route::resource('roles', RoleController::class);
    });


    /*
    |--------------------------------------------------------------------------
    | Purchase Orders
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:purchase_order'])->group(function () {

        Route::resource('purchase-orders', PurchaseOrderController::class);

        Route::get('purchase-orders/{id}/print-spk', [PurchaseOrderController::class, 'printSpk'])
            ->name('purchase-orders.print-spk');

        Route::post('purchase-orders/{id}/move', [PurchaseOrderController::class, 'moveToStage'])
            ->name('purchase-orders.move');

        Route::delete('purchase-orders/images/{id}', [PurchaseOrderController::class, 'deleteImage'])
            ->name('purchase-orders.delete-image');
    });


    /*
    |--------------------------------------------------------------------------
    | Desain
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:desain'])
        ->prefix('desain')
        ->name('desain.')
        ->group(function () {

            Route::get('/', [DesainController::class, 'index'])->name('index');

            Route::post('/{id}/update-status', [DesainController::class, 'updateStatus'])
                ->name('update-status');

            Route::post('/{id}/move', [DesainController::class, 'moveToStage'])
                ->name('move');

            Route::delete('/images/{id}', [DesainController::class, 'deleteImage'])
                ->name('delete-image');
        });


    /*
    |--------------------------------------------------------------------------
    | Printing
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:printing'])
        ->prefix('printing')
        ->name('printing.')
        ->group(function () {

            Route::get('/', [PrintingController::class, 'index'])->name('index');

            Route::post('/{id}/update-status', [PrintingController::class, 'updateStatus'])
                ->name('update-status');

            Route::post('/{id}/move', [PrintingController::class, 'moveToStage'])
                ->name('move');
        });


    /*
    |--------------------------------------------------------------------------
    | Press
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:press'])
        ->prefix('press')
        ->name('press.')
        ->group(function () {

            Route::get('/', [PressController::class, 'index'])->name('index');

            Route::post('/{id}/update-status', [PressController::class, 'updateStatus'])
                ->name('update-status');

            Route::post('/{id}/move', [PressController::class, 'moveToStage'])
                ->name('move');
        });


    /*
    |--------------------------------------------------------------------------
    | QC
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:qc'])
        ->prefix('qc')
        ->name('qc.')
        ->group(function () {

            Route::get('/', [QCController::class, 'index'])->name('index');

            Route::post('/{id}/update-status', [QCController::class, 'updateStatus'])
                ->name('update-status');

            Route::post('/{id}/move', [QCController::class, 'moveToStage'])
                ->name('move');
        });


    /*
    |--------------------------------------------------------------------------
    | Pengiriman
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:pengiriman'])
        ->prefix('pengiriman')
        ->name('pengiriman.')
        ->group(function () {

            Route::get('/', [PengirimanController::class, 'index'])->name('index');

            Route::post('/{id}/update-status', [PengirimanController::class, 'updateStatus'])
                ->name('update-status');

            Route::post('/{id}/move', [PengirimanController::class, 'moveToStage'])
                ->name('move');
        });


    /*
    |--------------------------------------------------------------------------
    | Kanban Board
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:kanban'])
        ->prefix('kanban')
        ->name('kanban.')
        ->group(function () {

            Route::get('/', [KanbanController::class, 'index'])->name('index');

            Route::get('/{id}', [KanbanController::class, 'show'])
                ->name('show');
        });


    /*
    |--------------------------------------------------------------------------
    | ✅ Completed Orders (Permission Required)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['permission:completed_orders'])
        ->prefix('completed-orders')
        ->name('completed-orders.')
        ->group(function () {

            Route::get('/', [CompletedOrderController::class, 'index'])
                ->name('index');

            Route::get('/{id}', [CompletedOrderController::class, 'show'])
                ->name('show');

            Route::get('/{id}/pdf', [CompletedOrderController::class, 'exportPdf'])
                ->name('pdf');
        });

});
