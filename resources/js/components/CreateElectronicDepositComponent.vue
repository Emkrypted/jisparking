<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Depósito
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
                                <label for="exampleInputEmail1">Fecha de Recaudación</label>
                                <input type="date"
                                class="form-control" 
                                id="exampleInputEmail1"
                                v-bind:max="datePickerOptions"
                                v-model="form.collection_date" 
                                placeholder="Ingresa la fecha de la recaudación" 
                                required>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Sucursal</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-on:change="getRemainingAmount"
                                v-model="form.branch_office_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                </select>
                            </div>
                            <div class="alert alert-danger" role="alert" v-if="form.branch_office_id != null && collection_total_amount < 1">
                                Usted no tiene recaudación para el día seleccionado. Por consiguiente no podrá subir un depósito.
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">N° de Depósito</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.deposit_number" placeholder="Ingresa el número del depósito" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Recaudado</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                disabled
                                v-model="collection_amount">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Depósitado. Te falta por depositar <strong>$ {{ formatCollectionAmount(collection_total_amount - deposit_total_amount - transbank_total_amount) }}</strong></label>
                                <input type="number" class="form-control" id="exampleInputEmail1" v-model="form.deposit_amount" placeholder="Ingresa el monto del depósito" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Fecha de Depósito</label>
                                <input type="date" class="form-control" id="exampleInputEmail1" v-model="form.deposit_date" placeholder="Ingresa la fecha de depósito" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Fotografía del Depósito</label>
                                <input ref="file" accept="image/jpeg, image/png, image/gif" type="file" class="form-control" v-on:change="onFileChange" required>
                            </div>
                            <button
                            type="submit"
                            :disabled="((form.collection_date != '') 
                            && (form.branch_office_id != null) 
                            && (form.deposit_number != '') 
                            && (form.deposit_amount != '') 
                            && (form.deposit_date != '')
                            && (form.description != '')
                            && (collection_total_amount > 0)
                            && noFile) ? !isDisabled : isDisabled"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>

                            <router-link to="/deposit" class="btn btn-danger btn-icon-split">
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
            this.getDate();
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
            getDate() {
                var d = new Date();
                d.setDate(d.getDate()-5);
                const ye = new Intl.DateTimeFormat('en', { year: 'numeric' }).format(d);
                const mo = new Intl.DateTimeFormat('en', { month: 'numeric' }).format(d);
                const da = new Intl.DateTimeFormat('en', { day: 'numeric' }).format(d);

                if(ye < 10) {
                    var new_year = '0' + ye;
                } else {
                    var new_year = ye;
                }
                if(mo < 10) {
                    var new_month = '0' + mo;
                } else {
                    var new_month = mo;
                }
                if(da < 10) {
                    var new_day = '0' + da;
                } else {
                    var new_day = da;
                }
                this.date_picker = new_year +'-'+ new_month + '-' + new_day;
            },
            getRemainingAmount() {
                axios.get('/api/electronic_collection/amount/'+this.form.branch_office_id+'/'+this.form.collection_date+'?api_token='+App.apiToken)
                .then(response => {
                    this.collection_total_amount = response.data.data;

                    this.collection_amount = response.data.data;
                });

                axios.get('/api/electronic_deposit/amount/'+this.form.branch_office_id+'/'+this.form.collection_date+'?api_token='+App.apiToken)
                .then(response => {
                    this.deposit_total_amount = response.data.data;
                });

                axios.get('/api/transbank/amount/'+this.form.branch_office_id+'/'+this.form.collection_date+'?api_token='+App.apiToken)
                .then(response => {
                    this.transbank_total_amount = response.data.data;
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
                formData.append('collection_date', this.form.collection_date);
                formData.append('deposit_number', this.form.deposit_number);
                formData.append('deposit_amount', this.form.deposit_amount);
                formData.append('created_at', this.form.deposit_date);
                formData.append('collection_amount', this.collection_total_amount);
                formData.append('file', this.file);

                axios.post('/api/deposit/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createDeposit.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/electronic_deposit');
            }
        },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    collection_date: '',
                    deposit_number: '',
                    deposit_amount: '',
                    deposit_date: ''
                },
                date_less_five: '',
                noFile: false,
                branch_office_posts: [],
                remaining_amount: 'N/A',
                collection_total_amount: 0,
                collection_amount: 0,
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