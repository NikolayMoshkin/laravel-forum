<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavouritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        return $reply->favouriteToggle();
//        Favourite::create([
//           'user_id' => auth()->id(),
//            'favourited_id' => $reply->id,
//            'favourited_type' => get_class($reply)
//        ]);

    }
}
