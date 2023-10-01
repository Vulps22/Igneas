<?php

use App\Http\Controllers\HomeController;
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
	if(Auth::check()){
		return redirect()->route('home');
	}
	return view('welcome');
});

Auth::routes([
	'register' => false,
	'logout' => false,
	'login' => false,
]);

//Unauthorised routes
Route::get('/login', 'App\Http\Controllers\Auth\LoginController@index')->name('login');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@create')->name('register');
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');

//Authorised routes
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/profile', ProfileEditor::class)->name('profile.editor');
Route::get('/profile/{userId}', ProfileView::class)->name('profile.view');

//messenger routes
Route::get('/messenger', 'App\Http\Controllers\MessengerController@index')->name('messenger');
Route::get('/messenger/{userId}', 'App\Http\Controllers\MessengerController@index')->name('conversation');
Route::post('messenger/send', 'App\Http\Controllers\MessengerController@createMessage')->name('send.message');
Route::get('messenger/{conversation_id}/messages', 'App\Http\Controllers\MessengerController@getMessages')->name('get.messages');
Route::get('messenger/find/{conversationId}', 'App\Http\Controllers\MessengerController@getConversation')->name('get.conversation');

//userController routes
Route::post('/set_user_location', 'App\Http\Controllers\UserController@set_user_location')->name('set_user_location');
Route::post('/save_user_profile', 'App\Http\Controllers\UserController@save_user_profile')->name('profile.save');

Route::post('/components/{component}', function ($component) {
	$data = json_decode(request()->getContent(), true);
	return view('components.' . $component, $data)->render();
});
