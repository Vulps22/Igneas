<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
	return (int) $user->id === (int) $id;
});

Broadcast::channel("conversation.user.{id}", function ($user, $id) {
	error_log('Broadcast channel conversation.user.{id} user id: ' . $user->id);
	return true;
	$conversation = $user->conversations()->where('id', $id)->first();
	if ($conversation) {
		error_log('Broadcast channel conversation.user.{id} returning true');
		return true;
	} else 
	{
		error_log('Broadcast channel conversation.user.{id} returning false');
		return false;
	}
});
