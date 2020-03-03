<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        props: ['attributes'],
        data() {
            return {
                locked: false,
            }
        },
        created() {
            this.locked = this.attributes.locked == 0 ? false: true;
            window.events.$on('thread-lock-toggle', data => {
                this.toggleLock(data);
            });
        },
        mounted(){
            $('textarea').atwho({
                at: "@",
                delay: 500,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {name: query}, function(usernames){
                            callback(usernames)
                        })
                    }
                }
            })
        },
        methods: {
            deleteThread() {
                let confirmDelete = confirm("Удалить статью?");
                if (confirmDelete) {
                    let url = window.location.pathname;
                    axios.delete(url)
                        .then(function (response) {
                            window.location.replace('/threads');
                        })
                }
            },

            toggleLock(locked){
                this.locked = locked == 0 ? false: true;
            }
        }
    }
</script>

<style scoped>

</style>
