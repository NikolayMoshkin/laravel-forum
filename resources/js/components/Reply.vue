<template>
    <div :id="'reply-'+id" class="card" style="margin-top: 1em">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8"><a :href="'/profiles/' + data.owner.name" v-text=" data.owner.name"></a>
                    ответил {{data.created_at}}:
                </div>
                <div class=" col-md-4 text-right reply-menu">
                    @can('update', $reply)
                    <i class="mr-3 fa fa-pencil text-muted update-reply" aria-hidden="true" @click="editing=true"
                       data-reply-id='{{$reply->id}}'></i>
                    <i class="mr-3 fa fa-trash text-muted delete-reply" aria-hidden="true" @click="deleteReply"
                       data-reply-id='{{$reply->id}}'></i>
                    @endcan
                    <strong class="likes-count">{{$reply->favourites_count}}</strong>
                    <a class="{{$reply->isFavourited()? 'blue':'grey'}}" href="#">
                        <i class="fa fa-thumbs-up mark-favourite" @click="favourite" aria-hidden="true"
                           data-user-id='{{$reply->owner->id}}' data-reply-id='{{$reply->id}}'></i>
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
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                }).then(function (response) {
                    flash('Комментарий обновлен');
                });
                this.editing = false;
            },
            favourite(event) {
                let likeElement = event.target;
                let replyId = likeElement.dataset.replyId;
                axios.post('/replies/' + replyId + '/favourites')
                    .then(function (res) {
                        let menu = likeElement.closest('.reply-menu');
                        menu.querySelector('.likes-count').innerText = res.data;
                        let thumbsUpElem = menu.querySelector('a');
                        thumbsUpElem.className = thumbsUpElem.className === 'grey' ? 'blue' : 'grey';
                    })
            },
            deleteReply(event) {
                let confirmDelete = confirm("Удалить ответ?");
                if (confirmDelete) {
                    let replyId = event.target.dataset.replyId;
                    axios.delete('/replies/' + replyId)
                        .then(function (response) {
                            let elem = document.querySelector('#reply-' + replyId);
                            elem.parentNode.removeChild(elem);
                            flash('Комментарий удален');
                            window.reduceRepliesCounter();
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>
