<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Coach\CoachController;
use App\Http\Controllers\Coach\CoachVerificationController;
use App\Http\Controllers\Coach\SalleController as CoachSalleController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Coach\SpecialityController as CoachSpecialityController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Community\CommunityController;
use App\Http\Controllers\Community\CommentController;
use App\Http\Controllers\Community\LikeController as CommunityLikeController;
use App\Http\Controllers\Community\MembershipController;
use App\Http\Controllers\Community\PostController as CommunityPostController;
use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Sport\SportController;
use App\Http\Controllers\Salle\SalleController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Coach\OpinionController as CoachOpinionController;

use App\Models\Trainee;
use App\Models\Coach;
use App\Models\Salle;
use App\Models\Opinion;
use PhpParser\Node\Expr\FuncCall;

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
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{salle}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{salle}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::resource('communities', CommunityController::class);
    Route::post('/communities/{community}/join', [MembershipController::class, 'store'])->name('communities.join');
    Route::delete('/communities/{community}/leave', [MembershipController::class, 'destroy'])->name('communities.leave');
    Route::get('/posts/create', [CommunityPostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [CommunityPostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', [CommunityPostController::class, 'show'])->name('posts.show');
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/posts/{post}/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/like', [CommunityLikeController::class, 'store'])->name('posts.like');
    Route::resource('roles', RoleController::class);
    Route::resource('sports', SportController::class);
    Route::resource('salles', SalleController::class);
    Route::resource('coaches', CoachController::class);
    Route::post('/coaches/{coach}/opinions', [OpinionController::class, 'store'])->name('opinions.store');

    //reports

    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::patch('/reports/{report}/cancel', [ReportController::class, 'cancel'])->name('reports.cancel');
    
    //User with opinions
    
    Route::get('/opinions/{opinion}', [OpinionController::class, 'show'])->name('opinions.show');
    Route::put('/opinions/{opinion}', [OpinionController::class, 'update'])->name('opinions.update');
    Route::delete('/opinions/{opinion}', [OpinionController::class, 'destroy'])->name('opinions.destroy');
    Route::resource('profile', ProfileController::class);


    Route::middleware('coach')->group(function () {

        //salles 

        Route::get('/coach/salles', [CoachSalleController::class, 'index'])->name('coach.salles');
        Route::get('/coach/salles/create', [CoachSalleController::class, 'create'])->name('coach.salles.create');
        Route::get('/coach/salles/{salle}/edit', [CoachSalleController::class, 'edit'])->name('coach.salles.edit');
        Route::put('/coach/salles/{salle}', [CoachSalleController::class, 'update'])->name('coach.salles.update');
        Route::delete('/coach/salles/{salle}', [CoachSalleController::class, 'destroy'])->name('coach.salles.destroy');
        Route::post('/coach/salles', [CoachSalleController::class, 'store'])->name('coach.salles.store');
        
        //Coach with opinions

        Route::get('/coach/opinions', [CoachOpinionController::class, 'index'])->name('coach.opinions');
        
        //Specialities

        Route::get('/specialities', [CoachSpecialityController::class, 'index'])->name('coach.specialities.index');
        Route::post('/specialities', [CoachSpecialityController::class, 'store'])->name('coach.specialities.store');
        Route::put('/specialities/{speciality}', [CoachSpecialityController::class, 'update'])->name('coach.specialities.update');
        Route::delete('/specialities/{speciality}', [CoachSpecialityController::class, 'destroy'])->name('coach.specialities.destroy');
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
});
