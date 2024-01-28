<?php

use App\Http\Controllers\HomeController;
use App\Http\Middleware\isAuthenticated;
use App\Livewire\ProfileEditor;
use App\Livewire\ProfileView;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
	if (isAuthenticated::authenticated()) return redirect('/home');
	return view('welcome');
});

Auth::routes([
	'register' => false,
	'logout' => false,
	'login' => false,
]);


//Authorised routes
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth.check');
Route::get('/profile', ProfileEditor::class)->name('profile.editor')->middleware('auth.check');
Route::get('/profile/{userId}', ProfileView::class)->name('profile.view')->middleware('auth.check');

//messenger routes
Route::get('/messenger', 'App\Http\Controllers\MessengerController@index')->name('messenger')->middleware('auth.check');
Route::get('/messenger/{userId}', 'App\Http\Controllers\MessengerController@index')->name('conversation')->middleware('auth.check');
Route::post('messenger/send', 'App\Http\Controllers\MessengerController@createMessage')->name('send.message')->middleware('auth.check');
Route::get('messenger/{conversation_id}/messages', 'App\Http\Controllers\MessengerController@getMessages')->name('get.messages')->middleware('auth.check');
Route::get('messenger/find/{conversationId}', 'App\Http\Controllers\MessengerController@getConversation')->name('get.conversation')->middleware('auth.check');

//userController routes
Route::post('/set_user_location', 'App\Http\Controllers\UserController@set_user_location')->name('set_user_location')->middleware('auth.check');
Route::post('/save_user_profile', 'App\Http\Controllers\UserController@save_user_profile')->name('profile.save')->middleware('auth.check');
Route::post('/user_profile_image', 'App\Http\Controllers\UserController@save_user_profile_image')->name('profile.save.image')->middleware('auth.check');
Route::delete('/user_profile_image', 'App\Http\Controllers\UserController@delete_user_profile_image')->name('profile.delete.image')->middleware('auth.check');
