@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($threads as $thread)
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5 class="card-title">
                                        @if($thread->hasUpdatesFor())
                                            <strong>
                                                <a href={{$thread->path()}}>{{$thread->title}}</a>
                                                <small style="color: rgba(249,17,0,0.63)"> обновлено</small>
                                            </strong>
                                        @else
                                            <a href={{$thread->path()}}>{{$thread->title}}</a>
                                        @endif
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                Автор: <a href="{{route('profile', $thread->owner->name)}}">{{$thread->owner->name}}</a>
                                            </h6>

                                        </div>
                                    </div>
                                    <div>  {{$thread->body}}</div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{$thread->path()}}"> {{$thread->replies_count}} ответов</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            <span class="fa fa-eye"></span> {{$thread->visits()->count()}}
                        </div>
                    </div>
                @endforeach

                <div class="mt-2">
                    {{$threads->links()}}
                </div>

            </div>
            <div class="col-md-4">
                @if(count($trending))
                    <div class="card">
                        <div class="card-header">Top 5 популярных постов</div>
                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($trending as $thread)
                                    <li class="list-group-item">
                                        <a href="{{$thread->path}}">{{$thread->title}}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

        </div>




@endsection
