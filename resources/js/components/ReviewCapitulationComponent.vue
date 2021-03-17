<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Revisar Rendición
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="form-group text-center">
                            <img v-bind:src="image" class="card-img-top img-responsive" alt="Card">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Fecha del Documento</label>
                            <input type="date" 
                            class="form-control" 
                            id="exampleInputEmail1"
                            v-model="form.document_date" 
                            placeholder="Ingresa la fecha del documento" 
                            >
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">N° de Documento </label>
                            <input 
                            type="number" 
                            class="form-control" 
                            id="exampleInputEmail1"
                            v-model="form.document_number" placeholder="Ingresa el número de documento" 
                            required>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Tipo de DTE</label>
                            <select class="form-control" id="exampleFormControlSelect1"
                            v-model="form.dte_type_id"
                                >
                                <option :value="null">-Seleccionar-</option>
                                <option value="39">Boleta</option>
                                <option value="33">Factura</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Tipo de Rendición</label>
                            <select 
                                class="form-control"
                                id="exampleFormControlSelect1"
                                v-model="form.capitulation_type_id"
                                >
                                <option :value="null">-Seleccionar-</option>
                                <option value="1">Fondo por Rendir</option>
                                <option value="2">Rendición de Gastos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Sucursal</label>
                            <select 
                            class="form-control" 
                            id="exampleFormControlSelect1"
                            v-model="form.branch_office_id"
                            >
                                <option :value="null">-Seleccionar-</option>
                                <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ capitalizeFirstLetter(branch_office_post.branch_office) }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Tipo de Gasto</label>
                            <select 
                            class="form-control" 
                            id="exampleFormControlSelect1"
                            v-model="form.expense_type_id"
                            >
                                <option :value="null">-Seleccionar-</option>
                                <option v-for="expense_type_post in expense_type_posts" :key="expense_type_post.expense_type_id" :value="expense_type_post.expense_type_id">{{ expense_type_post.expense_type }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Descripción</label>
                            <input 
                            type="number" 
                            class="form-control" 
                            id="exampleInputEmail1" 
                            v-model="form.description"
                            placeholder="Ingresa la descripción de la rendición" 
                            required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Monto </label>
                            <input 
                            type="number" 
                            class="form-control" 
                            id="exampleInputEmail1"
                            v-model="form.amount" placeholder="Ingresa el monto a rendir" 
                            required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Periodo</label>
                            <input
                                type="month"
                                v-model="form.period" 
                                class="form-control"
                                placeholder="Ingresa el periodo de la rendición"
                            >
                        </div>
                        <button 
                        @click="acceptCapitulation"
                        class="btn btn-success btn-icon-split"
                        >
                            <span class="icon text-white-50">
                                <i class="fas fa-times"></i>
                            </span>
                            <span class="text">Aceptar</span>
                        </button>

                        <button 
                        @click="rejectCapitulation"
                        class="btn btn-danger btn-icon-split"
                        >
                            <span class="icon text-white-50">
                                <i class="fas fa-times"></i>
                            </span>
                            <span class="text">Rechazar</span>
                        </button>

                        <router-link to="/capitulation" class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-times"></i>
                            </span>
                            <span class="text">Cancelar</span>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    import moment from 'moment'

    export default {
        created() {
            this.getBranchOfficeList();
            this.getExpenseTypeList();
            this.getPost();
            this.getSupport();
        },
        data: function() {
            return {
                form: {
                    document_date: '',
                    capitulation_type_id: '',
                    branch_office_id: null,
                    expense_type_id: null,
                    dte_type_id: null,
                    description: '',
                    amount: '',
                    document_number: '',
                    period: ''
                },
                image: '',
                branch_office_posts: [],
                expense_type_posts: []
            }
        },
        methods: {
            capitalizeFirstLetter(value) {
                return value.charAt(0).toUpperCase() + value.slice(1);
            },
            formatCollectionAmount(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
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
            },
            getPost() {
                axios.get('/api/capitulation/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.getSupport(this.$route.params.id);
                    this.$set(this.form, 'document_date', moment(this.post.document_date).format('YYYY-MM-DD'));
                    this.$set(this.form, 'capitulation_type_id', this.post.capitulation_type_id);
                    this.$set(this.form, 'dte_type_id', this.post.dte_type_id);
                    this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                    this.$set(this.form, 'expense_type_id', this.post.expense_type_id);
                    this.$set(this.form, 'description', this.post.description);
                    this.$set(this.form, 'amount', this.post.amount);
                    this.$set(this.form, 'period', this.post.period);
                    this.$set(this.form, 'document_number', this.post.document_number);
                });
            },
            onFileChange(e){
                this.file = e.target.files[0];
                this.noFile = e.target.files.length;
            },
            getSupport(id) {
                if(id != null) {
                    axios.get('/api/capitulation/support/'+id+'?api_token='+App.apiToken)
                    .then(response => {
                        this.image = response.data.data;
                    });
                }
            },
            /**
             * Accept a collection
             *
             * @return boolean
             */
            acceptCapitulation(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
   
                formData.append('collection_date', this.form.collection_date);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('dte_type_id', this.form.dte_type_id);
                formData.append('deposit_number', this.form.deposit_number);
                formData.append('deposit_amount', this.form.deposit_amount);
                formData.append('expense_type_id', this.form.expense_type_id);
                formData.append('amount', this.form.amount);
                formData.append('deposit_date', this.form.deposit_date);
                formData.append('description', this.form.description);
                formData.append('period', this.form.period);
                formData.append('status_id', 7);
                formData.append('file', this.file);
                formData.append('_method', 'PATCH')

                axios.post('/api/capitulation/'+this.$route.params.id+'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

                this.$router.push('/capitulation');
            },
            /**
             * Reject a collection
             *
             * @return boolean
             */
            rejectCapitulation(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
   
                formData.append('collection_date', this.form.collection_date);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('dte_type_id', this.form.dte_type_id);
                formData.append('deposit_number', this.form.deposit_number);
                formData.append('deposit_amount', this.form.deposit_amount);
                formData.append('expense_type_id', this.form.expense_type_id);
                formData.append('amount', this.form.amount);
                formData.append('deposit_date', this.form.deposit_date);
                formData.append('description', this.form.description);
                formData.append('period', this.form.period);
                formData.append('status_id', 14);
                formData.append('file', this.file);
                formData.append('_method', 'PATCH')

                axios.post('/api/capitulation/'+this.$route.params.id+'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

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