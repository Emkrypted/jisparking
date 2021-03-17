<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Imputar Rendición
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="imputeCapitulation" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Periodo</label>
                                <input
                                type="month"
                                v-model="form.period" 
                                class="form-control"
                                placeholder="Ingresa el periodo de la rendición"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Comentario</label>
                                <input
                                type="text" 
                                v-model="form.comment" 
                                class="form-control"
                                placeholder="Ingresa un comentario a la imputación"
                                >
                            </div>
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Imputar</span>
                            </button>
                            <router-link to="/capitulation" class="btn btn-danger btn-icon-split">
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
                    comment: '',
                    period: '',
                    split_dte_id: null
                },
                postsSelected: "",
                dte_type_posts: [],
                region_posts: [],
                branch_office_posts: [],
                collection_post: null,
                z_inform_number: 'N/A'
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
                formData.append('dte_id', this.$route.params.id);
                formData.append('comment', this.form.comment);
                formData.append('period', this.form.period);

                axios.post('/api/capitulation/impute?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.imputeCapitulation.reset(); // This will clear that form

                this.$awn.success("El registro ha sido imputado", {labels: {success: "Éxito"}});

                this.$router.push('/capitulation');
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
