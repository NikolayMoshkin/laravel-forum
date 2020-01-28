<template>
    <div>
        <h2 v-text='user.name'>
        </h2>
        <h5>создан: {{user.created_at.toString()}}</h5>
        <img class="mb-2" :src="avatar" alt="" height="100">
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <image-upload name="avatar" @loaded="onLoad"></image-upload>
            </div>
        </form>
        <div>Всего постов: {{user.threads_count}}</div>
    </div>
</template>

<script>
    import ImageUpload from "./ImageUpload.vue";

    export default {
        name: "AvatarForm",
        props: ['user'],

        components: {
            ImageUpload
        },

        data() {
            return {
                avatar: this.user.avatar_path
            };
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);

            }
        },

        methods: {
            onLoad(avatar){
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },

            persist(file) {
                let data = new FormData();
                data.append('avatar', file);
                axios.post('/api/users/' + this.user.name + '/avatar', data)
                    .then(() => flash('Аватар обновлен'));
            }
        }
    }
</script>

<style scoped>

</style>
