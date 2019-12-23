<template>
    <div class="alert text-center alert-flash"
         :class="'alert-' + level"
         v-show="show"
         v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                level: 'success',
                show: false,
            }
        },

        created() {
            if (this.message) {
                this.flash({message:this.message});
            }
            window.events.$on('flash', data => {
                this.flash(data);
            });
        },

        methods:{
            flash(data){
                console.log('flash starts: ' + data);
                this.body = data.message;
                this.level = data.level? data.level : this.level;
                this.show = true;

                this.hide();
            },
            hide() {
                setTimeout(()=>
                    this.show = false, 3000)
            }
        }
    }
</script>


<style>
    .alert-flash {
        position: fixed;
        right: 2em;
        bottom: 2em;
    }
</style>
