@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Форум</div>
                    <div class="card-body">
                        @foreach($threads as $thread)
                            <div class="row">
                                <div class="col-md-8">
                                    @if($thread->hasUpdatesFor())
                                        <strong><a href={{$thread->path()}}>{{$thread->title}}</a>
                                            <small style="color: rgba(249,17,0,0.63)"> обновлено</small>
                                        </strong>
                                    @else
                                        <a href={{$thread->path()}}>{{$thread->title}}</a>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-8">
                                            Автор: <a href="{{route('profile', $thread->owner->name)}}">{{$thread->owner->name}}</a>
                                        </div>
                                    </div>
                                    <div>  {{$thread->body}}</div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{$thread->path()}}"> {{$thread->replies_count}} ответов</a>
                                </div>
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
                <div class="mt-2">
                    {{$threads->links()}}
                </div>

            </div>

        </div>

    </div>




@endsection
