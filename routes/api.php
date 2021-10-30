<?php

use App\Http\Controllers\ApiHistoryContoller;
use App\Http\Controllers\ApiProductController;
use App\Http\Controllers\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->group(function() {
    Route::get('/product', [ApiProductController::class, 'index'])->name('home');

    Route::middleware('IsUserAdmin')->group(function () {
        Route::get('/product/{id}' , [ApiProductController::class, 'detail']);
        Route::get('/history', [ApiHistoryContoller::class, 'index']);
        Route::get('/preview', [ApiHistoryContoller::class, 'preview']);

        Route::post('/history', [ApiHistoryContoller::class, 'store']);
        Route::post('/product', [ApiProductController::class, 'store'])->name('storeProduct');
        Route::put('/product/{id}', [ApiProductController::class, 'update']);
        Route::delete('/product/{id}', [ApiProductController::class, 'destroy']);
        Route::delete('/images/{id}', [ApiProductController::class, 'delete_image']);
    });

    Route::get('/logout', [ApiUserController::class, 'logout']);
});

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::view('/register', 'register')->name('register');
    
    Route::post('/register', [ApiUserController::class, 'register']);
    Route::post('/login', [ApiUserController::class, 'login']); 
    
});
