<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LaptopController;
use App\Http\Controllers\Admin\CriteriaController;
use App\Http\Controllers\User\DecisionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', function () {
    return view('layouts.app');
});
Route::get('/admin', function () {
    return view('layouts.admin');
});
Route::get('/user/dashboard', function () {
    return view('user.dashboard');
});
// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm' ])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(callback: function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // User dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Decision process
    Route::get('/decision', [DecisionController::class, 'showCriteriaForm'])->name('decision.form');
    Route::post('/decision', [DecisionController::class, 'processDecision'])->name('decision.process');
    Route::get('/history', [DecisionController::class, 'showHistory'])->name('decision.history');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // Laptop management
        Route::resource('laptops', LaptopController::class);
        
        // Criteria management
        Route::resource('criteria', CriteriaController::class);
        
        // Criteria comparison
        Route::get('/criteria-comparison', [CriteriaController::class, 'showComparisonForm'])->name('criteria.comparison');
        Route::post('/criteria-comparison', [CriteriaController::class, 'storeComparisons']);
    });
});