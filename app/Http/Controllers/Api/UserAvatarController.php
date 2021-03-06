<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    public function store()
    {
        \request()->validate([
           'avatar' => 'required|image'
        ]);

        auth()->user()->update([
            'avatar_path' => \request()->file('avatar')->store('avatars', 'public') //TODO: быстро сохранять файлы сразу с hash именем
        ]);

        return response([], 204);
    }
}
