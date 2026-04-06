<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Coach\CoachController;
use App\Http\Controllers\Coach\CoachVerificationController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Community\CommunityController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Sport\SportController;
use App\Http\Controllers\Salle\SalleController;

use App\Models\Trainee;
use App\Models\Coach;
use App\Models\Salle;
use App\Models\Opinion;

Route::get('/', [WelcomeController::class, 'index'])->middleware(\App\Http\Middleware\isGuest::class)->name('welcome');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])
    ->name('logout');

    
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->middleware('auth')->name('home');
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
    Route::post('/coach-verifications', [CoachVerificationController::class, 'store'])->name('coach-verifications.store');
    Route::resource('communities', CommunityController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('sports', SportController::class);
    Route::resource('salles', SalleController::class);
    Route::resource('coaches', CoachController::class);
});
