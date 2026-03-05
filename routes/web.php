<?php

use App\Http\Controllers\CapitalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/capital', [CapitalController::class, 'index'])->name('capital.index');
Route::post('/capital', [CapitalController::class, 'store'])->name('capital.store');

Route::resource('products', ProductController::class)->except(['show', 'destroy']);

Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
