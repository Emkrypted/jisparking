<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Pagar Documento Electrónico
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="imputeCollection" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Total($)</label>
                                <input
                                type="number"
                                disabled
                                v-model="form.amount" 
                                class="form-control"
                                placeholder="Ingresa el monto del DTE"
                                >
                            </div>

                            <hr>

                            <div class="form-group" v-if="rol_id == 1 || rol_id == 11">
                                <label for="exampleFormControlSelect1">Tipo de Pago</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.payment_type_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="1">Depósito</option>
                                    <option :value="2">Transferencia Electrónica</option>
                                    <option :value="3">Nota de Crédito</option>
                                </select>
                            </div>

                            <div class="form-group" v-if="rol_id == 1 || rol_id == 11">
                                <label for="exampleInputEmail1">Fecha de Pago</label>
                                <input type="date" 
                                
                                class="form-control" 
                                id="exampleInputEmail1" 
                                v-model="form.payment_date" 
                                placeholder="Ingresa la fecha de pago" 
                                required>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Comentario</label>
                                <input
                                type="text" 
                                v-model="form.comment" 
                                class="form-control"
                                placeholder="Ingresa un comentario al DTE"
                                >
                            </div>
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Pagar</span>
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
            this.getExpenseTypeList();
            this.getRol();
            this.getBranchOfficeList();
        },
        data: function() {
            return {
                form: {
                    comment: '',
                    period: '',
                    split_dte_id: null,
                    payment_type_id: null,
                    branch_office_id: null,
                    payment_date: '',
                    amount: '',
                    amount: '',
                    expense_type_id: null,
                    dte_version_id: ''
                },
                rol_id: this.rol_id,
                postsSelected: "",
                dte_type_posts: [],
                region_posts: [],
                branch_office_posts: [],
                expense_type_posts: [],
                collection_post: null,
                z_inform_number: 'N/A',
                splits: [{
                    split_amount: '',
                    split_period: ''
                }]
            }
        },
        methods: {
            add() {
                this.splits.push({
                    split_amount: '',
                    split_period: ''
                })
            },
            getPost() {
                axios.get('/api/dte/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    this.$set(this.form, 'amount', this.post.amount);
                    this.$set(this.form, 'dte_version_id', this.post.dte_version_id);
                    this.$set(this.form, 'expense_type_id', this.post.expense_type_id);
                });
            },
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('dte_id', this.$route.params.id);
                console.log(JSON.stringify(this.splits));
                formData.append('splits', JSON.stringify(this.splits));
                formData.append('comment', this.form.comment);
                formData.append('payment_type_id', this.form.payment_type_id);
                formData.append('payment_date', this.form.payment_date);
                formData.append('branch_office_id', this.form.branch_office_id);

                axios.post('/api/dte/impute?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.imputeCollection.reset(); // This will clear that form

                this.$awn.success("El registro ha sido imputado", {labels: {success: "Éxito"}});

                this.$router.push('/dte');
            },
            getExpenseTypeList() {
                axios.get('/api/expense_type?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
                });
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
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
