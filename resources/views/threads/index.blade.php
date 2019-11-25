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
                                    <a href={{$thread->path()}}>{{$thread->title}}</a>
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
            </div>
        </div>

    </div>




@endsection
