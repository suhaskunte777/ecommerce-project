<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('products', ProductController::class);
Route::get('products/soft-deleted', [ProductController::class, 'indexSoftDeleted']);
Route::get('products/{product}/soft-deleted', [ProductController::class, 'showSoftDeleted']);
Route::put('products/{product}/soft-delete', [ProductController::class, 'softDelete']);
Route::put('products/{product}/restore', [ProductController::class, 'restore']);

Route::apiResource('products/{product}/reviews', ReviewController::class);
Route::get('products/{product}/reviews/soft-deleted', [ReviewController::class, 'indexSoftDeleted']);
Route::get('products/{product}/reviews/{review}/soft-deleted', [ReviewController::class, 'showSoftDeleted']);
Route::put('products/{product}/reviews/{review}/soft-delete', [ReviewController::class, 'softDelete']);
Route::put('products/{product}/reviews/{review}/restore', [ReviewController::class, 'restore']);
