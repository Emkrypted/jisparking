<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Asiento Manual
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createManualSeat" enctype="multipart/form-data">
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
                                <label for="exampleFormControlSelect1">Tipo de Gasto</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.expense_type_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="expense_type_post in expense_type_posts" :key="expense_type_post.expense_type_id" :value="expense_type_post.accounting_account">{{ expense_type_post.expense_type }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto</label>
                                <input
                                type="text" 
                                v-model="form.amount" 
                                class="form-control"
                                placeholder="Ingresa el monto del asiento"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Periodo</label>
                                <input type="month" class="form-control" 
                                id="exampleInputEmail1" 
                                v-model="form.period" 
                                placeholder="Ingresa el periodo del asiento" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">¿Debe tener IVA?</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.tax_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="1">Si</option>
                                    <option :value="2">No</option>
                                </select>
                            </div>
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>
                            <router-link to="/manual_seat" class="btn btn-danger btn-icon-split">
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
            this.getExpenseTypeList();
        },
        data: function() {
            return {
                form: {
                    tax_id: null,
                    branch_office_id: null,
                    expense_type_id: null,
                    amount: '',
                    period: ''
                },
                postsSelected: "",
                expense_type_posts: [],
                branch_office_posts: []
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
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('expense_type_id', this.form.expense_type_id);
                formData.append('amount', this.form.amount);
                formData.append('period', this.form.period);
                formData.append('tax_id', this.form.tax_id);

                axios.post('/api/manual_seat/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createManualSeat.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/manual_seat');
            },
            getDteTypeList() {
                axios.get('/api/dte_type?api_token='+App.apiToken)
                .then(response => {
                    this.dte_type_posts = response.data.data;
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
