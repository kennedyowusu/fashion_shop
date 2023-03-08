<?php

use App\Http\Controllers\API\AuthenticationController;
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

    // Logout route
    Route::post('logout', [AuthenticationController::class, 'logout'])->name('auth.logout');
});


// Public routes
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
