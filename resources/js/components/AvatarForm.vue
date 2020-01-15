<template>
    <div>
        <h2 v-text='user.name'>
        </h2>
        <h5>создан: {{user.created_at.toString()}}</h5>
        <img :src="avatar" alt="" height="100">
        <form v-if="canUpdate" method="POST"
              enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="avatar">
            </div>
            <button class="btn btn-primary" type="submit">Обновить</button>
        </form>
        <div>Всего постов: {{user.threads_count}}</div>
    </div>
</template>

<script>
    export default {
        name: "AvatarForm",
        props: ['user'],
        computed: {
            data(){
                return{
                    avatar:''
                };
            },

            canUpdate(){
                return this.authorize(user => user.id === this.user.id);

            }
        }
    }
</script>

<style scoped>

</style>
