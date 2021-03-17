<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Editar Factura
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="editTicket" enctype="multipart/form-data">
                           
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto</label>
                                <input
                                type="text" 
                                v-model="form.amount" 
                                class="form-control"
                                placeholder="Ingresa el monto bruto de la boleta"
                                >
                            </div>
                            <button
                            class="btn btn-success btn-icon-split"
                            >
                                <span class="icon text-white-50">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span class="text">Actualizar</span>
                            </button>
                            <router-link to="/ticket" class="btn btn-primary btn-icon-split">
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
            this.getPost();
        },
        data: function() {
            return {
                form: {
                    dte_type_id: null,
                    branch_office_id: null,
                    commune_id: null,
                    region_id: null,
                    rut: '',
                    address: '',
                    client: '',
                    email: '',
                    amount: '',
                    period: ''
                },
                postsSelected: "",
                commune_posts: [],
                dte_type_posts: [],
                expense_type_posts: [],
                region_posts: [],
                branch_office_posts: [],
                collection_post: null,
                z_inform_number: 'N/A'
            }
        },
        methods: {
            getPost() {
                axios.get('/api/dte/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                    this.$set(this.form, 'dte_type_id', this.post.dte_type_id);
                    this.$set(this.form, 'rut', this.post.rut);
                    this.$set(this.form, 'client', this.post.user.names);
                    this.$set(this.form, 'email', this.post.user.email);
                    this.$set(this.form, 'amount', this.post.amount);
                    this.$set(this.form, 'folio', this.post.created_at);
                });
            },
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('amount', this.form.amount);
                formData.append('status_id', 24);

                axios.post('/api/dte/update/'+ this.$route.params.id +'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.editTicket.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/bill');
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
            getCommuneList() {
                axios.get('/api/commune/search/region/'+ this.form.region_id +'?api_token='+App.apiToken)
                .then(response => {
                    console.log(response.data.data);
                    this.commune_posts = response.data.data;
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
