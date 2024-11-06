<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

$router->addRoute(
    ['get', 'post'], '/broadcasting/auth',
    '\\' . BroadcastController::class . '@authenticate'
);

Broadcast::channel('App.User.{id}', function ($user) {
    return Auth::check();
});


Broadcast::channel('user.{id}', function ($user,$id) {
    return Auth::user();
    // return $user->id == $toUserId;
});
