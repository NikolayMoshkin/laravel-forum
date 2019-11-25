@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="pb-2 mt-4 mb-2 border-bottom">
                <h2>{{$user->name}} <small>создан: {{$user->created_at->diffForHumans()}}</small></h2>
                <div>Всего постов: {{$user->threads_count}}</div>
            </div>

            @foreach($activities as $date => $activityList)
                <h5>Действия за {{$date}}:</h5>
                @foreach($activityList as $activity)
                    @if (view()->exists("profiles.activities.{$activity->type}"))
                        @include("profiles.activities.{$activity->type}", ['activity' => $activity])
                    @endif
                @endforeach

            @endforeach
        </div>
    </div>

@endsection
