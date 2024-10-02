<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\RevenueManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Welcome Page Route
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard and Product Management
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
Route::put('/products/{id}/approve', [AdminController::class, 'approve'])->name('products.approve');
Route::put('/products/{id}/decline', [AdminController::class, 'decline'])->name('products.decline');

// Analytics Route
Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');

// User Management
Route::get('/user-management', [UserManagementController::class, 'index'])->name('userManagement');
Route::put('/user-management/deactivate/{id}', [UserManagementController::class, 'deactivate'])->name('user.deactivate');
Route::put('/user-management/reactivate/{id}', [UserManagementController::class, 'reactivate'])->name('user.reactivate');
Route::delete('/user-management/delete/{id}', [UserManagementController::class, 'delete'])->name('user.delete');
Route::delete('/user-management/delete-product/{id}', [UserManagementController::class, 'deleteUserProduct'])->name('user.deleteProduct');

// Category Management
Route::get('/category-management', [CategoryController::class, 'index'])->name('categoryManagement');
Route::post('/category-management/add', [CategoryController::class, 'addCategory'])->name('category.add');
Route::delete('/category-management/remove/{category}', [CategoryController::class, 'removeCategory'])->name('category.remove');

// Revenue Management
Route::get('/revenue-management', [RevenueManagementController::class, 'index'])->name('revenue.management');
Route::post('/revenue-management/update-payment-status/{id}', [RevenueManagementController::class, 'updatePaymentStatus'])->name('revenue.updatePaymentStatus');

// Authentication Routes (Jetstream)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
