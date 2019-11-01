<div class="card" style="margin-top: 1em">
    <div class="card-header">{{$reply->owner->name}} ответил {{$reply->created_at->diffForHumans()}}:</div>
    <div class="card-body">
        {{$reply->body}}
    </div>
</div>
