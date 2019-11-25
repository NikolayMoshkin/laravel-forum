<script>
    export default {
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
                            console.log(response);
                            window.location.replace('/threads');
                        })
                }
            }
        }
    }
</script>

<style scoped>

</style>
