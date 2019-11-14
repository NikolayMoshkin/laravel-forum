<div class="card" style="margin-top: 1em">
    <div class="card-header">
        <div class="row">
            <div class="col-md-8"><a href="/profiles/{{$reply->owner->name}}">{{$reply->owner->name}}</a> ответил {{$reply->created_at->diffForHumans()}}:</div>
            <div class=" col-md-4 text-right mark-favourite">
                <strong>{{$reply->favourites_count}}</strong> <a class="{{$reply->isFavourited()? 'blue':'grey'}}" href="#" data-user-id = '{{$reply->owner->id}}' data-reply-id = '{{$reply->id}}'><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
            </div>
        </div>

    </div>
    <div class="card-body">
        {{$reply->body}}
    </div>
</div>
