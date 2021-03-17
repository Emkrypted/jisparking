<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Emitir Documento Electrónico
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="sendDte" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tipo de Dte</label>
                                <input
                                type="text" 
                                v-model="form.dte" 
                                class="form-control"
                                disabled
                                placeholder="Ingresa el dte"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Folio</label>
                                <input
                                type="text" 
                                v-model="form.folio" 
                                class="form-control"
                                disabled
                                placeholder="Ingresa el folio"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Correo</label>
                                <input
                                type="email" 
                                v-model="form.email" 
                                class="form-control"
                                placeholder="Ingresa el correo"
                                >
                            </div>
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Enviar</span>
                            </button>
                            <router-link to="/dte" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span class="text">Cancelar</span>
                            </router-link>
                        </form>
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
                    email: '',
                    folio: '',
                    dte: ''
                },
                postsSelected: "",
                collection_post: null
            }
        },
        methods: {
            getPost() {
                axios.get('/api/dte/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.$set(this.form, 'dte', this.post.dte_type_id);
                    this.$set(this.form, 'folio', this.post.folio);
                });
            },
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('dte', this.form.dte);
                formData.append('folio', this.form.folio);
                formData.append('email', this.form.email);

                axios.post('/api/dte/send?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.sendDte.reset(); // This will clear that form

                this.$awn.success("El correo ha sido enviado", {labels: {success: "Éxito"}});

                this.$router.push('/dte');
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
