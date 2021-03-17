<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Emitir Factura
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
                                <label for="exampleFormControlSelect1">Sucursal</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.branch_office_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tipo de Documento</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.dte_type_id"
                                >
                                    <option :value="33">Factura</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">RUT</label>
                                <input v-on:blur="getCustomer" 
                                type="text" 
                                v-model="form.rut" 
                                class="form-control" 
                                v-mask="'########-N'"
                                placeholder="Ingresa el rut del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Razón Social</label>
                                <input
                                type="text" 
                                v-model="form.client" 
                                class="form-control"
                                placeholder="Ingresa la razón social del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Giro</label>
                                <input
                                type="text" 
                                v-model="form.activity" 
                                class="form-control"
                                placeholder="Ingresa el giro del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Correo</label>
                                <input
                                type="text" 
                                v-model="form.email" 
                                class="form-control"
                                placeholder="Ingresa el correo del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Región</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.region_id"
                                v-on:change="getCommuneList"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="region_post in region_posts" :key="region_post.region_id" :value="region_post.region_id">{{ region_post.region }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Comuna</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.commune_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="commune_post in commune_posts" :key="commune_post.commune_id" :value="commune_post.commune_id">{{ commune_post.commune }}</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Dirección</label>
                                <input
                                type="text" 
                                v-model="form.address" 
                                class="form-control"
                                placeholder="Ingresa la dirección del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto</label>
                                <input
                                type="text" 
                                v-model="form.amount" 
                                class="form-control"
                                placeholder="Ingresa el monto bruto del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Cantidad</label>
                                <input
                                type="number" 
                                v-model="form.quantity" 
                                class="form-control"
                                placeholder="Ingresa la cantidad de facturas"
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
                            <router-link to="/bill" class="btn btn-danger btn-icon-split">
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
                    dte_type_id: 33,
                    branch_office_id: null,
                    commune_id: null,
                    region_id: null,
                    rut: '',
                    address: '',
                    client: '',
                    email: '',
                    amount: '',
                    activity: '',
                    quantity: 1
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
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('dte_type_id', this.form.dte_type_id);
                formData.append('rut', this.form.rut);
                formData.append('client', this.form.client);
                formData.append('activity', this.form.activity);
                formData.append('email', this.form.email);
                formData.append('address', this.form.address);
                formData.append('amount', this.form.amount);
                formData.append('quantity', this.form.quantity);
                formData.append('region_id', this.form.region_id);
                formData.append('commune_id', this.form.commune_id);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('creation_date', this.form.creation_date);

                axios.post('/api/dte/generate?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCollection.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/bill');
            },
            getCustomer() {
                axios.get('/api/customer/'+this.form.rut+'?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data.body;
                    console.log(this.post);
                    if(this.post != null) {
                        this.$set(this.form, 'client', this.post.contribuyente);
                        this.$set(this.form, 'email', this.post.email);
                        this.$set(this.form, 'address', this.post.direccion);
                        this.$set(this.form, 'activity', this.post.giro);
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
