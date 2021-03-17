<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Emitir Nota de Crédito
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createCollection" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Pregunta</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.option_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="1">¿Desea borrar indefinidamente el registro y generar una nota de crédito?</option>
                                    <option :value="2">¿Desea cambiar el estado y generar una nota de crédito?</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Comentario</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.comment"
                                placeholder="Ingresa el comentario">
                            </div>
                            
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Emitir</span>
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
        },
        data: function() {
            return {
                form: {
                    option_id: null,
                    comment: ''
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
                formData.append('option_id', this.form.option_id);
                formData.append('dte_id', this.$route.params.id);
                formData.append('comment', this.form.comment);

                axios.post('/api/creditnote/generate?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCollection.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/dte');
            },
            getCustomer() {
                axios.get('/api/customer/'+this.form.rut+'?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    if(this.post != null) {
                        this.$set(this.form, 'client', this.post.user.names);
                        this.$set(this.form, 'email', this.post.user.email);
                        this.$set(this.form, 'address', this.post.address);
                    }
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
