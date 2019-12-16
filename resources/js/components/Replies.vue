<template>
    <div v-for="reply in items">
        <reply :data="reply"></reply>
    </div>
</template>

<script>
    import Reply from "./Reply.vue";
    export default {
        components:{Reply},
        props: ['data'],
        data() {
            return {
                itmes: this.data,
            }
        }

    }
</script>
