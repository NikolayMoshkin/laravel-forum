<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index', 'show');
    }

    public function index()
    {
//        $threads = Thread::latest()->get();
        $threads = Thread::latest()->get();
        return view('threads.index', compact('threads'));
    }

    public function show($channel_id, Thread $thread)
    {
        Carbon::setLocale('ru');  //изменить язык пакета Carbon
        return view('threads.show', compact('thread'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:2',
            'body'  => 'required|min:2',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'title' => $request->title,
            'channel_id' =>$request->channel_id,
            'body'=>$request->body,
            'user_id'=>auth()->id(),
        ]);

        return redirect('/threads/'.$thread->id);
    }

    public function create()
    {
        return view('threads.create');
    }
}
