<?php

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\User\UserController;
use App\Route\Route;


//register auth route like change-password etc
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/update-profile', [UserController::class, 'update']);
    Route::post('/update-profile', [UserController::class, 'update_profile']);

    Route::post('/logout', [AuthController::class, 'destroy']);
    Route::get('/user/profile/{name}', [UserController::class, 'showBySlug']);
    // Route::get('/send-mail', [UserController::class, 'mail']);

    // Route::post('/send-mail', [UserController::class, 'samplemail']);
});





//prevent the auth user to go back from login or register page
Route::middleware('redirectIfAuthenticated')->group(function () {
    Route::get('/login', [AuthController::class, 'show']);
    Route::get('/register', [AuthController::class, 'register']);
});



//register public routes like /about-us, /welcome etc
Route::get('/', [HomeController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'create']);


return Route::routes();
