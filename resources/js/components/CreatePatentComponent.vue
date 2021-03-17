<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Patente
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createPatent" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Sucursal</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.branch_office_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Periodo</label>
                                <input type="month" class="form-control" min="2020-01" v-model="form.date">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Imagen</label>
                                <input ref="file" accept="application/pdf" type="file" class="form-control" v-on:change="onFileChange" required>
                            </div>
                            <button
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>

                            <router-link to="/patent" class="btn btn-danger btn-icon-split">
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
            this.getBranchOfficeList();
        },
        methods: {
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            onFileChange(e){
                this.file = e.target.files[0];
                this.noFile = e.target.files.length;
            },
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('date', this.form.date);
                formData.append('file', this.file);

                axios.post('/api/patent/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createPatent.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/patent');
            }
        },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    date: ''
                },
                date_less_five: '',
                noFile: false,
                branch_office_posts: [],
                collection_total_amount: 0,
                deposit_total_amount: 0,
                transbank_total_amount: 0,
                datePickerOptions: this.date_picker
            }
        },
        computed: {
            isDisabled() {
                return true;
            }
        }
    }
</script>