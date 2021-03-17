<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Revisar Requerimiento
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createRequirement" enctype="multipart/form-data">
                            <div v-if="form.requirement_type_id == 1">
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
                                    <label for="exampleFormControlSelect1">¿Es factura?</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.is_bill"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option :value="1">Si</option>
                                        <option :value="2">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Monto Bruto</label>
                                    <input type="number" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.amount" 
                                    placeholder="Ingresa el monto bruto de la boleta o factura" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Motivo</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.expense_type_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="expense_type_post in expense_type_posts" :key="expense_type_post.expense_type_id" :value="expense_type_post.expense_type_id">{{ expense_type_post.expense_type }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">RUT del Proveedor</label>
                                    <input
                                    type="text" 
                                    v-model="form.supplier_rut" 
                                    class="form-control" 
                                    v-mask="'########-#'"
                                    placeholder="Ingresa el rut del proveedor"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fecha de Boleta o Factura</label>
                                    <input type="date" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.bill_ticket_date" 
                                    placeholder="Ingresa la fecha de la boleta o factura" 
                                    required>
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
                                    <span class="text">Continuar</span>
                                </button>

                                <router-link to="/requirement" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancelar</span>
                                </router-link>
                            </div>
                            <div v-if="form.requirement_type_id == 2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sucursal</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    disabled
                                    v-model="form.branch_office_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Motivo</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    disabled
                                    v-model="form.reason"
                                    >
                                        <option value="null">-Seleccionar-</option>
                                        <option value="Licencia Médica">Licencia Médica</option>
                                        <option value="Vacaciones">Vacaciones</option>
                                        <option value="Reemplazo">Reemplazo</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Colaborardor a Sustituir</label>
                                    <input type="text" class="form-control" 
                                    id="exampleInputEmail1" 
                                    disabled
                                    v-model="form.employee_to_replace" 
                                    placeholder="Ingresa el colaborardor a sustituir">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Monto Bruto</label>
                                    <input type="number" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.amount" 
                                    placeholder="Ingresa el monto del honorario" required>
                                </div>
                                <button
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Continuar</span>
                                </button>

                                <router-link to="/requirement" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancelar</span>
                                </router-link>
                            </div>
                            <div v-if="form.requirement_type_id == 3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sucursal</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.branch_office_id"
                                    disabled
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Motivo</label>
                                    <input type="text" class="form-control" 
                                    id="exampleInputEmail1" 
                                    disabled
                                    v-model="form.reason">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Monto Neto</label>
                                    <input type="number" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.amount" 
                                    placeholder="Ingresa el monto neto" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Inicio del Proyecto</label>
                                    <input type="date" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.start_date" 
                                    placeholder="Ingresa el inicio del proyecto" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fin del Proyecto</label>
                                    <input type="date" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.end_date" 
                                    placeholder="Ingresa el fin del proyecto" required>
                                </div>
                                <button
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Continuar</span>
                                </button>

                                <router-link to="/requirement" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancelar</span>
                                </router-link>
                            </div>
                            <div v-if="form.requirement_type_id == 4">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sucursal</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.branch_office_id"
                                    disabled
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Motivo</label>
                                    <input type="text" class="form-control" 
                                    id="exampleInputEmail1" 
                                    disabled
                                    v-model="form.reason">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Monto Neto</label>
                                    <input type="number" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.amount" 
                                    placeholder="Ingresa el monto neto" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Inicio del Proyecto</label>
                                    <input type="date" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.start_date" 
                                    placeholder="Ingresa el inicio del proyecto" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fin del Proyecto</label>
                                    <input type="date" class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.end_date" 
                                    placeholder="Ingresa el fin del proyecto" required>
                                </div>
                                <button
                                type="submit"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Continuar</span>
                                </button>

                                <router-link to="/requirement" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancelar</span>
                                </router-link>
                            </div>
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
            this.getPost();
        },
        methods: {
            formatCollectionAmount(value) {
                let val = (value/1).toFixed(0).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
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
                formData.append('is_bill', this.form.is_bill);
                formData.append('amount', this.form.amount);
                formData.append('supplier_rut', this.form.supplier_rut);
                formData.append('expense_type_id', this.form.expense_type_id);
                formData.append('bill_ticket_date', this.form.bill_ticket_date);
                formData.append('start_date', this.form.start_date);
                formData.append('end_date', this.form.end_date);
                formData.append('file', this.file);

                axios.post('/api/requirement/update/'+ this.$route.params.id +'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createRequirement.reset(); // This will clear that form

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

                this.$router.push('/requirement');
            },
            getExpenseTypeList() {
                axios.get('/api/expense_type?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
                });
            },
            getPost() {
                axios.get('/api/requirement/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                
                    this.$set(this.form, 'requirement_type_id', this.post.requirement_type_id);

                    if(this.post.requirement_type_id == 2) {
                        axios.post('/api/honorary/requirement/'+ this.post.requirement_id +'?api_token='+App.apiToken)
                        .then(response => {
                            this.post = response.data.data;
                            this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                            this.$set(this.form, 'reason', this.post.reason);
                            this.$set(this.form, 'employee_to_replace', this.post.user.names);
                        });
                    }
                    
                    if(this.post.requirement_type_id == 3) {
                        axios.post('/api/maintenance/requirement/'+ this.post.requirement_id +'?api_token='+App.apiToken)
                        .then(response => {
                            this.post = response.data.data;
                            this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                            this.$set(this.form, 'reason', this.post.reason);
                        });
                    }

                    if(this.post.requirement_type_id == 4) {
                        axios.post('/api/publicity/requirement/'+ this.post.requirement_id +'?api_token='+App.apiToken)
                        .then(response => {
                            this.post = response.data.data;
                            this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                            this.$set(this.form, 'reason', this.post.reason);
                        });
                    }
                });
            },
        },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    is_bill: null,
                    amount: '',
                    expense_type_id: null,
                    bill_ticket_date: null,
                    requirement_id: '',
                    reason: '',
                    requirement_type_id: '',
                    start_date: '',
                    end_date: '',
                    employee_to_replace: null
                },
                date_less_five: '',
                noFile: false,
                branch_office_posts: [],
                expense_type_posts: [],
                remaining_amount: 'N/A',
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