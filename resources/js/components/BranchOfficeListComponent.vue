<template>
    <div>
        <div class="form-group">
            <label for="exampleFormControlSelect1">Sucursal</label>
            <select class="form-control" id="exampleFormControlSelect1">
                <option>-Seleccionar-</option>
                <option v-for="post in posts" :key="post.branch_office_id">{{ post.branch_office }}</option>
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
                axios.get('/api/branch_office')
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
