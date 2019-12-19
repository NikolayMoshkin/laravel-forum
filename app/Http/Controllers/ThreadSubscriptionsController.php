<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
{
    public function store(Channel $channel, Thread $thread)
    {
        $thread->subscribe(auth()->id());
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        $thread->unsubscribe(auth()->id());
    }


}
