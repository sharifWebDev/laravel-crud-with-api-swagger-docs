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

// User-specific private channel
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Admin channel for user management
Broadcast::channel('admin.users', function ($user) {
    return $user->is_admin === true; // Adjust based on your admin check
});

// Quiz updates channel
Broadcast::channel('quiz.{quizId}', function ($user, $quizId) {
    return true; // Or add specific authorization logic
});
