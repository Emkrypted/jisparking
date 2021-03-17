<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Refrescar DTE por RUT
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="refreshDTE" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">RUT</label>
                                <input v-on:blur="getSupplier" 
                                type="text" 
                                v-model="form.rut" 
                                class="form-control" 
                                v-mask="'########-#'"
                                placeholder="Ingresa el rut del proveedor"
                                >
                            </div>
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Refrescar</span>
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
            this.getDteTypeList();
            this.getRegionList();
            this.getBranchOfficeList();
            this.getExpenseTypeList();
        },
        data: function() {
            return {
                form: {
                    rut: ''
                },
                postsSelected: "",
                dte_type_posts: [],
                expense_type_posts: [],
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
                formData.append('rut', this.form.rut);

                axios.post('/api/dte/refresh?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.refreshDTE.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/dte');
            },
            getSupplier() {
                axios.get('/api/supplier/'+this.form.rut+'?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    this.$set(this.form, 'client', this.post.user.names);
                    this.$set(this.form, 'email', this.post.user.email);
                    this.$set(this.form, 'address', this.post.address);
                });
            },
            getDteTypeList() {
                axios.get('/api/dte_type?api_token='+App.apiToken)
                .then(response => {
                    this.dte_type_posts = response.data.data;
                });
            },
            getRegionList() {
                axios.get('/api/region?api_token='+App.apiToken)
                .then(response => {
                    console.log(response.data.data);
                    this.region_posts = response.data.data;
                });
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            getExpenseTypeList() {
                axios.get('/api/expense_type?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
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
