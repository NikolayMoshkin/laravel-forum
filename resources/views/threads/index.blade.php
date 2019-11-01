@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Форум</div>
                    <div class="card-body">
                        @foreach($threads as $thread)
                            <a href="/threads/{{$thread->id}}">{{$thread->title}}</a>
                            <div>  {{$thread->body}}</div>
                            <hr>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
