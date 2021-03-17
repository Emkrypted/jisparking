<template>
    <div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Comuna</label>
            <select class="form-control" id="exampleFormControlSelect1">
                <option>-Seleccionar-</option>
                <option v-for="post in posts" :key="post.commune_id">{{ post.commune }}</option>
            </select>
        </div>
    </div>
    
</template>

<script>
    export default {
        created() {
            this.getList();
        },
        methods: {
            getList() {
                axios.get('/api/commune')
                .then(response => {
                    this.posts = response.data.data;
                });
            }
        },
        data: function() {
            return {
                postsSelected: "",
                posts: []
            }
        }
    }
</script>
