<script>
    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                body: this.attributes.body,
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                }).then(function (response) {
                    flash('Комментарий обновлен');
                });
                this.editing = false;
            },
            favourite(event){
                let likeElement = event.target;
                let replyId = likeElement.dataset.replyId;
                axios.post('/replies/' + replyId + '/favourites')
                    .then(function(res){
                        let menu = likeElement.closest('.reply-menu');
                        menu.querySelector('.likes-count').innerText = res.data;
                        let thumbsUpElem  =  menu.querySelector('a');
                        thumbsUpElem.className = thumbsUpElem.className === 'grey' ? 'blue' : 'grey';
                    })
            },
            deleteReply(event){
                let confirmDelete = confirm("Удалить ответ?");
                if (confirmDelete) {
                    let replyId = event.target.dataset.replyId;
                    axios.delete('/replies/'+ replyId)
                        .then(function (response) {s
                            let elem = document.querySelector('#reply-'+ replyId);
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
