<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\ProductsController;
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


Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //profile
    Route::get('/profile', [ProfileController::class, 'profile']);

    //products
    Route::get('/products', [ProductsController::class, 'products']);
    Route::get('/product/{id}', [ProductsController::class, 'read']);
    Route::post('/product/create', [ProductsController::class, 'create']);
    Route::post('/product/update/{id}', [ProductsController::class, 'update']);
    Route::post('/product/delete/{id}', [ProductsController::class, 'delete']);
});
