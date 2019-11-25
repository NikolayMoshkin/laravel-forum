<reply :attributes="{{$reply}}" inline-template>
    <div id='reply-{{$reply->id}}' class="card" style="margin-top: 1em">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8"><a href="/profiles/{{$reply->owner->name}}">{{$reply->owner->name}}</a>
                    ответил {{$reply->created_at->diffForHumans()}}:
                </div>
                <div class=" col-md-4 text-right reply-menu">
                    @can('update', $reply)
                        <i class="mr-3 fa fa-pencil text-muted update-reply" aria-hidden="true" @click="editing=true"
                           data-reply-id='{{$reply->id}}'></i>
                        <i class="mr-3 fa fa-trash text-muted delete-reply" aria-hidden="true"
                           data-reply-id='{{$reply->id}}'></i>
                    @endcan
                    <strong class="likes-count">{{$reply->favourites_count}}</strong>
                    <a class="{{$reply->isFavourited()? 'blue':'grey'}}" href="#">
                        <i class="fa fa-thumbs-up mark-favourite" @click="favourite" aria-hidden="true" data-user-id='{{$reply->owner->id}}' data-reply-id='{{$reply->id}}'></i>
                    </a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class='form-control' v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Обновить</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Отмена</button>

            </div>
            <div v-else v-text="body">
{{--                {{$reply->body}}--}}
            </div>

        </div>
    </div>
</reply>
