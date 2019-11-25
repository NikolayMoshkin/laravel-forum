@component('profiles.activities.activity')
    @slot('heading')
        {{$activity->created_at->diffForHumans()}} удален пост
    @endslot

    @slot('body')

    @endslot
@endcomponent
