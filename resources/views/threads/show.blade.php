@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">{{$thread->owner->name}}</a> написал:
                        {{$thread->title}}</div>
                    <div class="card-body">
                        {{$thread->body}}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        @if(auth()->check())
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="/threads/{{$thread->id}}/replies" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" id="body" name="body" placeholder="Есть что ответить?" required>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-outline-secondary">
                        </div>
                    </form>
                </div>
            </div>
            <hr>
        @else
            <div class="row justify-content-center">
                <p><a href="/login">Войдите</a>, чтобы оставить комментарий.</p>
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h5>Ответы:</h5>
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

    </div>
@endsection
