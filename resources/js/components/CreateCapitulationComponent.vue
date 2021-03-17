<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Rendición
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createCapitulation" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label for="exampleInputEmail1">Fecha del Documento</label>
                                <input type="date" 
                                
                                class="form-control" 
                                id="exampleInputEmail1" 
                                v-model="form.document_date" 
                                placeholder="Ingresa la fecha del documento" 
                                required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">N° de Documento </label>
                                <input type="number" class="form-control" id="exampleInputEmail1" v-model="form.document_number" placeholder="Ingresa el número de documento" required>
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
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.capitulation_type_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option value="1">Fondo por Rendir</option>
                                    <option value="2">Rendición de Gastos</option>
                                </select>
                            </div>
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
                                    <option v-for="expense_type_post in expense_type_posts" :key="expense_type_post.expense_type_id" :value="expense_type_post.expense_type_id">{{ expense_type_post.expense_type }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Descripción</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.description" placeholder="Ingresa la descripción de la rendición" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto </label>
                                <input type="number" class="form-control" id="exampleInputEmail1" v-model="form.amount" placeholder="Ingresa el monto a rendir" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Foto</label>
                                <input ref="file" accept="image/jpeg, image/png, image/gif" type="file" class="form-control" v-on:change="onFileChange" required>
                            </div>
                            <button
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
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
        created() {
            this.getBranchOfficeList();
            this.getExpenseTypeList();
        },
        data: function() {
            return {
                form: {
                    capitulation_type_id: null,
                    dte_type_id: null,
                    document_number: '',
                    document_date: '',
                    branch_office_id: null,
                    expense_type_id: null,
                    description: '',
                    amount: ''
                },
                noFile: false,
                branch_office_posts: [],
                expense_type_posts: []
            }
        },
        methods: {
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            getExpenseTypeList() {
                axios.post('/api/expense_type/list?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
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
                formData.append('capitulation_type_id', this.form.capitulation_type_id);
                formData.append('dte_type_id', this.form.dte_type_id);
                formData.append('document_date', this.form.document_date);
                formData.append('document_number', this.form.document_number);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('expense_type_id', this.form.expense_type_id);
                formData.append('description', this.form.description);
                formData.append('amount', this.form.amount);
                formData.append('file', this.file);

                axios.post('/api/capitulation/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCapitulation.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

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