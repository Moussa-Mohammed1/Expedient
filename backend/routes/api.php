<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Community\CommunityController;
use App\Http\Controllers\Salle\SalleController;
use App\Http\Controllers\Sport\SportController;
use App\Http\Controllers\User\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
