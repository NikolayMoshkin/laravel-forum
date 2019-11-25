@component('profiles.activities.activity')
    @slot('heading')
        {{$activity->subject->created_at->diffForHumans()}} Создан пост <a
            href="{{$activity->subject->path()}}">{{$activity->subject->title}}</a>
    @endslot

    @slot('body')
        {{$activity->subject->body}}
    @endslot
@endcomponent
