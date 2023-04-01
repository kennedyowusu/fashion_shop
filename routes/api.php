<?php

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // User routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [UserController::class, 'show'])->where('user', '[0-9]+')->name('users.show');
    Route::put('users/{user}', [UserController::class, 'update'])->where('user', '[0-9]+')->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->where('user', '[0-9]+')->name('users.destroy');

    Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('users.updateProfile');

    // Category routes
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->where('category', '[0-9]+')->name('categories.show');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->where('category', '[0-9]+')->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->where('category', '[0-9]+')->name('categories.destroy');
    Route::get('categories/{category}/products', [CategoryController::class, 'getProductsByCategory'])->where('category', '[0-9]+')->name('categories.products');

    // Product routes
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->where('product', '[0-9]+')->name('products.show');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{product}', [ProductController::class, 'update'])->where('product', '[0-9]+')->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->where('product', '[0-9]+')->name('products.destroy');

    // Order routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->where('order', '[0-9]+')->name('orders.show');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::put('orders/{order}', [OrderController::class, 'update'])->where('order', '[0-9]+')->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->where('order', '[0-9]+')->name('orders.destroy');

    // Logout route
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('auth.logout');
});


// Public routes
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
