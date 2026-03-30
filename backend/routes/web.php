<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Community\CommunityController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Sport\SportController;
use App\Http\Controllers\Salle\SalleController;

use App\Models\Trainee;
use App\Models\Coach;
use App\Models\Salle;
use App\Models\Opinion;

Route::get('/', function () {

    $activeAthletes = Trainee::where('isBanned', false)->count();
    $verifiedCoaches = Coach::where('hasBadge', true)->count();
    $gymsCount = Salle::count();
    $citiesCount = Salle::distinct('city')->count('city');
    $avgRatingDb = Opinion::avg('rate');
    $averageRating = $avgRatingDb ? number_format($avgRatingDb, 1) : "0.0";

    return view('welcome', compact('activeAthletes', 'verifiedCoaches', 'gymsCount', 'citiesCount', 'averageRating'));
})->middleware(\App\Http\Middleware\isGuest::class);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.show');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.show');
Route::post('/register', [AuthController::class, 'register'])->name('register');
// Auth


Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'isGuest'])->group(function () {
    Route::resource('communities', CommunityController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('sports', SportController::class);
    Route::resource('salles', SalleController::class);
});

Route::get('/home', function () {
    return view('trainee.home');
})->middleware('auth');