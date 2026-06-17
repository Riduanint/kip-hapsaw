<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

// Redirect root to dashboard or landing page
Route::get('/', function () {
    if (Auth::check()) {
        $sk = 'tab_' . uniqid();
        session(["multi_auth.{$sk}" => Auth::id()]);
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard', ['sk' => $sk])
            : redirect()->route('user.dashboard', ['sk' => $sk]);
    }
    $criteria = \App\Models\Criterion::orderBy('code', 'asc')->get();
    return view('landing', compact('criteria'));
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('multi_tab_auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Panel Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Criteria CRUD
        Route::get('/criteria', [AdminController::class, 'criteriaIndex'])->name('criteria');
        Route::post('/criteria', [AdminController::class, 'criteriaStore'])->name('criteria.store');
        Route::put('/criteria/{id}', [AdminController::class, 'criteriaUpdate'])->name('criteria.update');
        Route::delete('/criteria/{id}', [AdminController::class, 'criteriaDestroy'])->name('criteria.destroy');
        
        // AHP Pairwise Comparisons
        Route::get('/ahp', [AdminController::class, 'ahpIndex'])->name('ahp');
        Route::post('/ahp', [AdminController::class, 'ahpUpdate'])->name('ahp.update');
        
        // Alternatives CRUD
        Route::get('/alternatives', [AdminController::class, 'alternativesIndex'])->name('alternatives');
        Route::post('/alternatives', [AdminController::class, 'alternativesStore'])->name('alternatives.store');
        Route::put('/alternatives/{id}', [AdminController::class, 'alternativesUpdate'])->name('alternatives.update');
        Route::delete('/alternatives/{id}', [AdminController::class, 'alternativesDestroy']);
        
        // Alternative Scores
        Route::get('/scores', [AdminController::class, 'scoresIndex'])->name('scores');
        Route::post('/scores', [AdminController::class, 'scoresUpdate'])->name('scores.update');
        
        // SAW Calculation & Ranking
        Route::get('/saw', [AdminController::class, 'sawIndex'])->name('saw');
        Route::post('/saw/lock', [AdminController::class, 'sawLock'])->name('saw.lock');
    });

    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/application', [UserController::class, 'application'])->name('application');
        Route::post('/profile', [UserController::class, 'profileUpdate'])->name('profile.update');
    });
});
