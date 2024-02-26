<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\UserAuthenticationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Middleware\Authenticate;
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

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle user authentication and token verification.
|
*/
Route::group([
    'prefix' => 'auth',
    'controller' => UserAuthenticationController::class,
], function () {
    Route::get('verify', 'authenticate');
});


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
|
| These routes handle user authentication, registration, and related actions.
|
*/
Route::group([
    'prefix' => 'user',
], function () {
    Route::post('login', 'App\Http\Controllers\Auth\LoginController@login');
    Route::post('create', 'App\Http\Controllers\Auth\RegisterController@create');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
|
| These routes handle user profile management and retrieval.
|
*/
Route::group([
    'prefix' => 'profile',
    'middleware' => Authenticate::class,
    'controller' => UserProfileController::class,
], function () {
    Route::post('save', 'save_user_profile');
    Route::patch('save_location', 'set_user_location');
    Route::post('save_profile_image', 'save_user_profile_image');
    Route::delete('delete_profile_image', 'delete_user_profile_image');
    Route::get('list', 'list');
    Route::get('{user}', 'get');
});

/*
|--------------------------------------------------------------------------
| Messenger Routes
|--------------------------------------------------------------------------
|
| These routes handle messenger related actions.
|
*/
Route::group([
    'prefix' => 'messenger',
    'middleware' => Authenticate::class,
    'controller' => MessengerController::class,
], function () {
    Route::get('list', 'list_conversations');
    Route::get('get_conversation_for', 'getConversationForUser');
    Route::get('get_conversation', 'getConversation');
    Route::post('message/create', 'createMessage');
});
/*
|--------------------------------------------------------------------------
| Image Routes
|--------------------------------------------------------------------------
|
| These routes handle image-related actions.
| DEBUG ONLY DO NOT EXPOSE TO PRODUCTION
|
*/
Route::group([
    'prefix' => 'image',
    'controller' => ImageController::class,
], function () {
 //   Route::post('store', 'store');
 //   Route::get('{image}', 'show');
});