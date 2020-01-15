<script>
    import 'jquery.caret';
    import 'at.js';

    export default {
        props: ['attributes'],
        data() {
            return {

            }
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
            }
        }
    }
</script>

<style scoped>

</style>
