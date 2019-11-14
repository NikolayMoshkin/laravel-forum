@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="pb-2 mt-4 mb-2 border-bottom">
                <h2>{{$user->name}} <small>создан: {{$user->created_at->diffForHumans()}}</small></h2>
                <div>Всего постов: {{$user->threads_count}}</div>
            </div>

            @foreach($threads as $thread)
                <div class="card mb-3">
                    <div class="card-header">
                        <a href="{{$thread->path()}}">{{$thread->title}}</a>
                        опубликован {{$thread->created_at->diffForHumans()}}
                    </div>
                    <div class="card-body">
                        {{$thread->body}}
                    </div>
                </div>
            @endforeach
            {{$threads->links()}}
        </div>
    </div>

@endsection
