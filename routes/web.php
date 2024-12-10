<?php

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\User\UserController;
use App\Route\Route;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/update-profile', [UserController::class, 'update']);
    Route::post('/update-profile', [UserController::class, 'update_profile']);

    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::get('/user/profile/{name}', [UserController::class, 'showBySlug']);




    //admin route
    Route::get('/admin/dashboard', [UserController::class, 'dashboard']);
});





Route::middleware('redirectIfAuthenticated')->group(function () {
    Route::get('/login', [AuthController::class, 'show']);
    Route::get('/register', [AuthController::class, 'register']);
});



Route::get('/', [HomeController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'create']);
Route::get('/user/{id}', [HomeController::class, 'show']);

return Route::routes();
