<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Videotutoriales
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createVideotutorial" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Título</label>
                                <input
                                type="text" 
                                v-model="form.title" 
                                class="form-control"
                                placeholder="Ingresa el título del videotutorial"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">URL del video</label>
                                <input
                                type="text" 
                                v-model="form.iframe" 
                                class="form-control"
                                placeholder="Ingresa la URL del videotutorial"
                                >
                            </div>
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>
                            <router-link to="/videotutorial" class="btn btn-danger btn-icon-split">
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
        data: function() {
            return {
                form: {
                    title: '',
                    iframe: ''
                }
            }
        },
        methods: {
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('title', this.form.title);
                formData.append('iframe', this.form.iframe);

                axios.post('/api/videotutorial/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createVideotutorial.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/videotutorial');
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
