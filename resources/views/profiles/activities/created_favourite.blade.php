@component('profiles.activities.activity')
    @slot('heading')
        {{$activity->subject->created_at->diffForHumans()}} понравился <a href="{{$activity->subject->favourited->path()}}"> комментарий </a>
    @endslot

    @slot('body')
        {{$activity->subject->favourited->body}}
    @endslot
@endcomponent
