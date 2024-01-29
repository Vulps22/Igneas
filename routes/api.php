<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//user API
Route::post('/user/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/user/create', 'App\Http\Controllers\Auth\RegisterController@create');

//Profile API
Route::post('/profile/save', 'App\Http\Controllers\UserProfileController@save_user_profile');
Route::post('/profile/save_location', 'App\Http\Controllers\UserProfileController@set_user_location');
Route::post('/profile/save_profile_image', 'App\Http\Controllers\UserProfileController@save_user_profile_image');
Route::post('/profile/delete_profile_image', 'App\Http\Controllers\UserProfileController@delete_user_profile_image');
Route::post('/profile/{user}', 'App\Http\Controllers\UserProfileController@get');

//Grid API
Route::post('/home/grid', 'App\Http\Controllers\HomeController@get_grid');
