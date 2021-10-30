<?php

use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('home');
    
    Route::middleware(['IsUserAdmin'])->group(function () {
        Route::get('/history', [HistoryController::class, 'index']);
        Route::get('/product/{id}', [ProductController::class, 'detail']);
        Route::get('/preview', [HistoryController::class, 'preview']);
        
        Route::post('/history', [HistoryController::class, 'store']);
        Route::post('/product', [ProductController::class, 'store'])->name('storeProduct');
        Route::delete('/product/{id}', [ProductController::class, 'destroy']);
        Route::put('/product/{id}', [ProductController::class, 'update']);
        Route::delete('/images/{id}', [ProductController::class, 'delete_image']);
    });
    Route::get('/logout', [UserController::class, 'logout']);
});

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::view('/register', 'register')->name('register');
    
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});