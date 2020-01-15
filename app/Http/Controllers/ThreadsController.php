<?php

namespace App\Http\Controllers;

use App\Channel;


use App\Filters\ThreadFilters;
use App\Thread;
use Illuminate\Http\Request;


class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index(Channel $channel, ThreadFilters $filters)
    {

        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()){
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    public function show($channel_id, Thread $thread)
    {
        if(auth()->check()){
            auth()->user()->read($thread);
        }

//        $thread->load('replies.owner');
//        return $thread->load('replies');
//        return Thread::withCount('replies')->first();  //считает кол-во replies

        $replies = $thread->replies()->paginate(15);

        return view('threads.show', compact('thread', 'replies'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:2|spamfree',
            'body'  => 'required|min:2|spamfree',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'title' => $request->title,
            'channel_id' =>$request->channel_id,
            'body'=>$request->body,
            'user_id'=>auth()->id(),
        ]);

        return redirect($thread->path())->with('flash', 'Заметка создана успешно!');
    }

    public function create()
    {
        return view('threads.create');
    }

    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();  //связанные replies удаляем через boot()

        return response([], 204);
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {

        $threads = Thread::latest()->filter($filters);
//        dd($threads->toSql()); //показывает чистый sql запрос

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->paginate(5);
        return $threads;
    }

}
