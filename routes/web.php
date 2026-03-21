<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapitalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/capital', [CapitalController::class, 'index'])->name('capital.index');
    Route::post('/capital', [CapitalController::class, 'store'])->name('capital.store');

    Route::resource('products', ProductController::class)->except(['show']); 

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::get('/sales/pos', [SaleController::class, 'pos'])->name('sales.pos');
    Route::post('/sales/pos', [SaleController::class, 'posStore'])->name('sales.pos.store');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::post('/dashboard/expense', [ExpenseController::class, '__invoke'])->name('dashboard.expense.store');
    Route::post('/dashboard/transfer', [TransferController::class, '__invoke'])->name('dashboard.transfer.store');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/health', function () {
    return response()->json(['status' => 'ok'], 200);
})->name('health');
