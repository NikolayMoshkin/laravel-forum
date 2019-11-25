<script>
    export default {
        props:['attributes'],
        data(){
            return{
                repliesCount: this.attributes.replies_count,
            }
        },
        created() {
            window.events.$on('reduceRepliesCounter', () => {
                console.log('reduceRepliesCounter starts, ' + this.repliesCount)
                this.repliesCount--;
            });
        },
        methods:{

        }

    }
</script>

<style scoped>

</style>
