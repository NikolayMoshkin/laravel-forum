<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\Trending;
use Illuminate\Http\Request;


class ThreadsController extends Controller
{

    public function __construct()
    {
        $this->middleware('verified')->except('index', 'show');
    }

    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($channel, $filters);

        if(request()->wantsJson()){
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    public function show($channel_id, Thread $thread, Trending $trending)
    {
        if(auth()->check()){
            auth()->user()->read($thread);
        }

//        $thread->load('replies.owner');
//        return $thread->load('replies');
//        return Thread::withCount('replies')->first();  //TODO:считает кол-во replies

        $trending->push($thread);
        $thread->visits()->record();

        $replies = $thread->replies()->paginate(15);

        return view('threads.show', compact('thread', 'replies'));
    }

    public function update($channel, Thread $thread)
    {
        if(\request()->has('locked')){
            if(! auth()->user()->isAdmin()){
                return response('', 403);
            }
            $thread->lock();
        }
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
            'body'=>$request->body,
            'channel_id' =>$request->channel_id,
            'user_id'=>auth()->id(),
            'slug' => $request->title, //далее вызывается мутатор setSlugAttribute в модели
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
//        dd($threads->toSql()); //TODO: показывает чистый sql запрос

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->paginate(5);
        return $threads;
    }

}
