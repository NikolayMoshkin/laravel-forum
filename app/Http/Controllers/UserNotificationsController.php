<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserNotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy(User $user, $notificationId)
    {
        auth()->user()->notifications()->findOrFail($notificationId)->markAsRead(); //notifications, markAsRead - методы трейта Notifiable
    }

    public function index(User $user)
    {
        return auth()->user()->unreadNotifications;
    }
}
