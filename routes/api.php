<?php

use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\Api\v1\AIController;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\B2BController;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CheckoutController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\WheelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/

// Public Authentication
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Catalog & Storefront
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/products/flash-sales', [ProductController::class, 'flashSales']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Cart Operations (Supports Guest & Authenticated)
Route::get('/cart', [CartController::class, 'getCart']);
Route::post('/cart/items', [CartController::class, 'addItem']);
Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
Route::post('/cart/coupon', [CartController::class, 'applyCoupon']);

// Gamification & Calculators & AI
Route::get('/wheel/config', [WheelController::class, 'config']);
Route::post('/wheel/spin', [WheelController::class, 'spin']);
Route::post('/ai/chat', [AIController::class, 'chat']);
Route::post('/b2b/calculate-import', [B2BController::class, 'calculateImport']);

// Authenticated User Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/checkout', [CheckoutController::class, 'checkout']);

    // B2B Retailer Portal
    Route::get('/b2b/dashboard', [B2BController::class, 'dashboard']);

    // Admin Control Center
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::post('/admin/products', [ProductController::class, 'store']);
    Route::get('/admin/b2b-requests', [AdminController::class, 'b2bRequests']);
    Route::post('/admin/b2b-requests/{id}/approve', [AdminController::class, 'approveB2B']);
    Route::post('/admin/b2b-requests/{id}/reject', [AdminController::class, 'rejectB2B']);
    Route::post('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus']);
    Route::post('/admin/settings', [AdminController::class, 'updateSettings']);
});
