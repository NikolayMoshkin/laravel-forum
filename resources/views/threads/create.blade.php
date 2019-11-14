@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Новый пост
                    </div>
                    <div class="card-body">
                        <form action="/threads" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="channel">Выберите канал</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Выбрать</option>
{{--                                    @foreach(App\Channel::all() as $channel)--}}
                                    @foreach($channels as $channel)
                                        <option value="{{$channel->id}}" {{old('channel_id') == $channel->id? 'selected' : ''}}>{{$channel->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Заголовок</label>
                                <input type="text" class="form-control" name="title" id="title" required
                                       value="{{old('title')}}">
                            </div>
                            <div class="form-group">
                                <label for="body">Что у вас на уме?</label>
                                <textarea name="body" id="body" cols="30" rows="10" class="form-control"
                                          required>{{old('body')}}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary">
                            </div>
                        </form>
                    </div>

                    @if(count($errors))
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
