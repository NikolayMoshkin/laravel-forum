<script>
    import SubsButton from "./SubsButton.vue";

    export default {
        props: ['attributes'],
        components: {
            SubsButton
        },
        data() {
            return {
                repliesCount: this.attributes.replies_count,
                lockBtnText: ''
            }
        },
        created() {
            this.lockBtnText = this.attributes.locked == 0? 'Закрыть' : 'Открыть'
            window.events.$on('reduceRepliesCounter', () => {
                console.log('reduceRepliesCounter starts, ' + this.repliesCount)
                this.repliesCount--;
            });
            window.events.$on('thread-lock-toggle', (locked) => {
                this.lockBtnText = locked == 0? 'Закрыть' : 'Открыть';
                }
            )
        },
        methods: {
            threadLockToggle: function () {
                axios.post('/locked-thread/' + this.attributes.slug).then((response) => {
                        window.events.$emit('thread-lock-toggle', response.data)
                    }
                );

            }

        }

    }
</script>

<style scoped>

</style>
