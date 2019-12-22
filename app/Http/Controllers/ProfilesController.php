<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $threads = $user->threads()->paginate(6);
//        $activities = $user->activities()->paginate(10);
        $activities = Activity::find(1);
        dd($activities);

        return view('profiles.show', compact('user', 'threads', 'activities'));
    }
}
