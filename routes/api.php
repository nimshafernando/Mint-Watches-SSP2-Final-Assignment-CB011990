<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;


// Registration route

Route::get('/my-products', [PaymentController::class, 'index']); // View user products
Route::put('/products/{productId}/mark-as-paid', [PaymentController::class, 'markAsPaid']); // Mark product payment as paid

// Route to fetch all products created by the logged-in user
Route::get('/user/products', [ProductController::class, 'getUserProducts']);

// Route to mark a product as paid
Route::put('/products/{id}/mark-paid', [ProductController::class, 'markAsPaid']);
Route::patch('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


//ratings and review

// Review routes
Route::post('/products/{id}/reviews', [ReviewController::class, 'addReview']);

// Get approved reviews for a product
Route::get('/products/{id}/reviews', [ReviewController::class, 'getApprovedReviews']);

// Approve a review (admin action)
Route::put('/reviews/{id}/approve', [ReviewController::class, 'approveReview']);

Route::delete('/profile/delete', [ProfileController::class, 'delete']);

//favourites

// Toggle favorite status
Route::post('/products/{productId}/toggle-favorite', [FavoriteController::class, 'toggleFavorite']);

// Get all favorite products for the logged-in user
Route::get('/favorites', [FavoriteController::class, 'getFavorites']);


Route::get('/profile', [ProfileController::class, 'show']);
Route::put('/profile/update', [ProfileController::class, 'update']); // Update profile
Route::put('/products/{id}', [ProductController::class, 'update']);


Route::get('/products', [ProductController::class, 'index']);
Route::post('/products', [ProductController::class, 'store']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);





Route::post('/payments', [PaymentController::class, 'store']);
