<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Mostrar Inducción
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <center>
                            <iframe
                            :src="iframe.src"
                            height="800" 
                            width="800"
                            frameborder="0" 
                            class="iframe" 
                            allowfullscreen="true" 
                            mozallowfullscreen="true" 
                            webkitallowfullscreen="true">
                            </iframe>
                         </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    export default {
        created() {
            this.getPost();
        },
        data: function() {
            return {
                form: {
                    iframe: ''
                },
                iframe: {
                    src: ''
                }
            }
        },
        methods: {
           getPost() {
                axios.get('/api/induction/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.$set(this.iframe, 'src', this.post.iframe);
                });
            }
        },
        computed: {
            isDisabled() {
                return true;
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
