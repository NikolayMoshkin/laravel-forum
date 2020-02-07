<script>
    export default {
        props: ['attributes'],
        data() {
            return {
                editing: false,
                body: this.attributes.body,
                oldBody: '',
                isBest: false
            }
        },
        created(){
            this.isBest = this.attributes.isBest;
            this.oldBody = this.attributes.body;
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.attributes.id) ? true : false;
            });
        },
        methods: {
            cancel() {
              this.editing = false;
              this.body = this.oldBody;
            },
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                })
                    .then(function (response) {
                        flash('Комментарий обновлен');
                    })
                    .catch(error => {
                        this.body = this.oldBody;
                        flash(error.response.data, 'danger');
                    });
                this.editing = false;
            },
            favourite(event) {
                let likeElement = event.target;
                let replyId = likeElement.dataset.replyId;
                console.log(event.target);
                console.log(event.target.dataset);
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
            },
           toggleBestReply(){
                axios.post('/replies/' + this.attributes.id + '/best')
                    .then(function (response) {
                        window.events.$emit('best-reply-selected', response.data);
                    });

            }
        }
    }
</script>

<style scoped>

</style>
