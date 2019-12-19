<script>
    import SubsButton from "./SubsButton.vue";
    export default {
        props:['attributes'],
        components:{
            SubsButton
        },
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
