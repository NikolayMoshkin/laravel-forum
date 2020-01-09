<template>
    <button :class="classes" @click="subscribe" v-text="active? 'Подписаться': 'Подписка оформлена'"></button>
</template>

<script>
    export default {
        name: "SubsButton",
        props:['active'],

        computed:{
            classes(){
                return ['btn', this.active ? 'btn-primary' : 'btn-outline-secondary'];
            }
        },

        methods:{
            subscribe(){
                let self = this;
                axios[this.active ? 'post' : 'delete'](location.pathname + '/subscriptions')
                    .then(function(){
                        self.active = !self.active;
                });
            }
        }

    }
</script>

<style scoped>

</style>
