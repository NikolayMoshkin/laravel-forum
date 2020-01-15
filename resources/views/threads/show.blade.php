@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <thread :attributes="{{$thread}}" inline-template>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-10">
                                    <img class="mr-2" src="{{$thread->owner->avatar()}}" alt="{{$thread->owner->name}}" height="30">
                                    <a href="/profiles/{{$thread->owner->name}}">{{$thread->owner->name}}</a>
                                    <div>
                                        <strong>{{$thread->title}}</strong>
                                    </div>
                                </div>
                                @can('delete', $thread)
                                    <div class="col-md-2 text-right">
                                        <a href="#">
                                            <i class="fa fa-trash text-muted" @click="deleteThread" aria-hidden="true"
                                               data-thread-id='{{$thread->id}}' id="deleteThread"></i>
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            {{$thread->body}}
                        </div>
                    </div>
                </thread>
                @if(count($thread->replies))
                    <hr>
                    <h5>Комментарии:</h5>
                @endif
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach
                <div style="margin-top: 1em">
                    {{$replies->links()}}
                </div>

                @if(auth()->check())
                    <div style="margin-top: 1em">
                        <form action="{{$thread->path()}}/replies" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="body" id="body" cols="30" rows="4"
                                          required placeholder="Есть что ответить?"></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-outline-secondary">
                            </div>
                        </form>
                    </div>
                @else
                    <div style="margin-top: 1em">
                        <p><a href="/login">Войдите</a>, чтобы оставить комментарий.</p>
                    </div>
                @endif
            </div>
            <div class="col-md-4">
                <thread-info :attributes='{{$thread}}' inline-template>
                    <div class="card">
                        <div class="card-body">
                            <p>Пост был опубликован <a
                                    href="/profiles/{{$thread->owner->name}}">{{$thread->owner->name}}</a> {{$thread->created_at->diffForHumans()}}
                                и на данный момент имеет <span v-text="repliesCount"></span> комментариев.
                            </p>
                            <p>
                                <subs-button :active={{json_encode(!$thread->isSubscribedTo)}}></subs-button>
                            </p>
                        </div>
                    </div>
                </thread-info>
            </div>
        </div>
    </div>

@endsection
