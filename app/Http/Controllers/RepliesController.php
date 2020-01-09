<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Forms\CreatePostForm;
use App\Notifications\YouWereMentioned;
use App\Reply;
use App\Thread;
use App\User;
use App\Utilities\Inspections\Spam;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Channel $channel
     * @param Thread $thread
     * @param Request $request
     * @param Spam $spam
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */

    public function store(Channel $channel, Thread $thread, Request $request, CreatePostForm $form)
    {
        try{
            $thread->addReply([
                'body' => $request->body,
                'user_id' => auth()->id(),
            ]);

            return back()->with('flash', 'Комментарий добавлен');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }


    }
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->delete();

        return response(['Reply deleted'], 204);
    }

    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        try{
            $request->validate([
                'body'=>'required|min:2|spamfree',
            ]);
            $reply->update(['body' => $request->body]);
            return response(['Reply updated'], 200);

        } catch (\Exception $e) {
            if ($request->expectsJson()){
                return response('Мы не можем сохранить ответ с таким содержанием', 422);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }

    }
}
