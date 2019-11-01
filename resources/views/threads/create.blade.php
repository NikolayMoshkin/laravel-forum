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
                                <label for="title">Заголовок</label>
                                <input type="text" class="form-control" name="title" id="title" required>
                            </div>
                            <div class="form-group">
                                <label for="body">Что у вас на уме?</label>
                                <textarea name="body" id="body" cols="30" rows="10" class="form-control"
                                          required></textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
