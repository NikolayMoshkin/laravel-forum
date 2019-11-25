<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Channel $channel, Thread $thread, Request $request)
    {
        $request->validate([
            'body'=>'required',
        ]);

        $thread->addReply([
            'body' => $request->body,
            'user_id' => auth()->id(),
        ]);

        return redirect($thread->path())->with('flash', 'Комментарий добавлен');
    }
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        return response([], 204);
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(['body' => $request->body]);
        return response([], 200);
    }
}
