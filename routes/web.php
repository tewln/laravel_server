<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

// Маршруты для авторизации
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

use App\Http\Controllers\UserController;
use App\Http\Controllers\UserContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\AdminController;

// Маршруты для пользователей
Route::prefix('users')->name('users.')->middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/edit', [UserController::class, 'edit'])->name('edit');
    Route::post('/update', [UserController::class, 'update'])->name('update');
    Route::get('/change-password', [UserController::class, 'changePasswordForm'])->name('change-password-form');
    Route::post('/change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::get('/add-phone', [UserContactController::class, 'create'])->name('add-phone-form');
    Route::post('/add-phone', [UserContactController::class, 'store'])->name('add-phone');
    Route::delete('/phones/{phoneNumber}', [UserContactController::class, 'destroy'])->name('remove-phone');
});

// Маршруты для товаров
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductController::class, 'show'])->name('show');
    Route::get('/{id}/availability', [ProductController::class, 'availability'])->name('availability');
    Route::get('/{id}/check-availability/{region}/{quantity?}', [ProductController::class, 'checkAvailabilityInRegion'])->name('check-availability');
    Route::get('/popular', [ProductController::class, 'getPopularProducts'])->name('popular');
});

// Маршруты для заказов (требуют аутентификации)
Route::prefix('orders')->name('orders.')->middleware('auth')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/{userId}/{createdAt}', [OrderController::class, 'show'])
        ->where('createdAt', '.*')
        ->name('show');
});

// Маршруты для складов
Route::prefix('warehouses')->name('warehouses.')->group(function () {
    Route::get('/', [WarehouseController::class, 'index'])->name('index');
    Route::get('/{id}', [WarehouseController::class, 'show'])->name('show');
    Route::get('/{id}/deliveries', [WarehouseController::class, 'deliveries'])->name('deliveries');
    Route::get('/stats/inventory-by-region', [WarehouseController::class, 'inventoryByRegion'])->name('inventory-by-region');
    Route::get('/search/products', [WarehouseController::class, 'searchProducts'])->name('search-products');
});

// Маршруты для административной панели
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/process-deliveries', [AdminController::class, 'processDeliveries'])->name('process-deliveries');
});