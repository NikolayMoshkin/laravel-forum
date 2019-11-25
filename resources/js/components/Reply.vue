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
                console.log(event);
                let likeElement = event.target;
                let replyId = likeElement.dataset.replyId;
                axios.post('/replies/' + replyId + '/favourites')
                    .then(function(res){
                        let menu = likeElement.closest('.reply-menu');
                        menu.querySelector('.likes-count').innerText = res.data;
                        let thumbsUpElem  =  menu.querySelector('a');
                        thumbsUpElem.className = thumbsUpElem.className === 'grey' ? 'blue' : 'grey';
                    })
            }
        }
    }
</script>

<style scoped>

</style>
