<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Transbank
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createTransbank" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mes</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.month_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="i in 12" :key="i" :value="i">{{ i }}</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Año</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.year_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="i in 10" :key="2019+i" :value="2019+i">{{ 2019+i }}</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Archivo</label>
                                <input ref="file" type="file" class="form-control" v-on:change="onFileChange" required>
                            </div>
                            <button
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>
                            <router-link to="/transbank" class="btn btn-danger btn-icon-split">
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
                    month_id: null,
                    year_id: null
                },
                noFile: false,
                file: null,
                postsSelected: "",
                branch_office_posts: [],
                cashier_posts: [],
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
                formData.append('month_id', this.form.month_id);
                formData.append('year_id', this.form.year_id);
                formData.append('file', this.file);

                axios.post('/api/transbank/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createTransbank.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/transbank');
            },
            onFileChange(e){
                this.file = e.target.files[0];
                this.noFile = e.target.files.length;
            },
            capitalizeFirstLetter(value) {
                return value.charAt(0).toUpperCase() + value.slice(1);
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
