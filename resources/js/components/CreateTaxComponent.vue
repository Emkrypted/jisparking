<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Iva
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createDeposit" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Mes</label>
                                <input type="month" class="form-control" min="2020-01" v-model="form.date">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Soporte</label>
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

                            <router-link to="/tax" class="btn btn-danger btn-icon-split">
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
                formData.append('date', this.form.date);
                formData.append('file', this.file);

                axios.post('/api/tax/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createDeposit.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/tax');
            }
        },
        data: function() {
            return {
                form: {
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