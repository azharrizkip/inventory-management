<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('login');
    }
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Auth::routes();


Route::middleware(['auth'])->group(function () {
    // Rute yang hanya dapat diakses oleh pengguna yang terautentikasi
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/add', [TransactionController::class, 'create'])->name('transactions.add');
    Route::post('/transactions/add', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionController::class, 'showDetail'])->name('transactions.detail');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::get('/get-serial-numbers/{product_id}', [TransactionController::class, 'getSerialNumbers']);

    Route::middleware(['role:superadmin'])->group(function () {
        // Rute yang hanya dapat diakses oleh pengguna dengan role superadmin
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::get('/products/add', [ProductController::class, 'create'])->name('products.add');
        Route::post('/products/add', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('/products/{serialNumber}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{serialNumber}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{serialNumber}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/report', [TransactionController::class, 'report'])->name('report');
    });
});



