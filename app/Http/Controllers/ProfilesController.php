<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = Activity::feed($user);

//        dd($activities);
        return view('profiles.show', compact('user', 'activities'));
    }

}
