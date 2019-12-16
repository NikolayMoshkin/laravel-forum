<script>
    import Replies from "../components/Replies.vue";
    export default {
        components: {Replies},
        props: ['attributes'],
        data() {
            return {

            }
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
