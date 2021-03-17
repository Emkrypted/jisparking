<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Recaudación
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
                                <label for="exampleInputEmail1">Fecha de la Recaudación</label>
                                <input type="date" class="form-control" id="exampleInputEmail1" 
                                v-model="form.created_at"
                                placeholder="Ingresa la fecha de la recaudación">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Sucursal</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-on:change="getCashiers"
                                v-model="form.branch_office_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ capitalizeFirstLetter(branch_office_post.branch_office) }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Caja</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.cashier_id"
                                v-on:change="displayLastZCorrelativeAndStartTicket"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="cashier_post in cashier_posts" :key="cashier_post.cashier_id" :value="cashier_post.cashier_id">{{ cashier_post.cashier }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">El último N° Informe Z ingresado fue <strong>{{ z_inform_number }}</strong></label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                :disabled="form.branch_office_id == null || form.cashier_id == null ? isDisabled : !isDisabled" 
                                v-model="form.z_inform_number"
                                placeholder="Ingresa el número de informe z">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto en Efectivo</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                v-model="form.gross_amount"
                                placeholder="Ingresa el monto bruto">
                            </div>
                            <div v-if="form.transbank_status_id == 1" class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto en Tarjeta</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                v-model="form.card_gross_amount"
                                placeholder="Ingresa el monto bruto en tarjeta">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">El último N° de boleta inicial ingresado fue <strong>{{ end_ticket_number }}</strong></label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.start_ticket"
                                placeholder="Ingresa el número de boleta inicial">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Boleta Final</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.end_ticket"
                                placeholder="Ingresa el número de boleta final">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tickets Liberados</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.released_tickets"
                                placeholder="Ingresa la cantidad de tickets liberados">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Foto</label>
                                <input ref="file" accept="image/jpeg, image/png, image/gif" type="file" class="form-control" v-on:change="onFileChange" required>
                            </div>
                            <button 
                            :disabled="((form.created_at != '') 
                            && (form.branch_office_id != null) 
                            && (form.cashier_id != null) 
                            && (form.z_inform_number != '')
                            && (form.gross_amount != '')
                            && (form.start_ticket != '')
                            && (form.end_ticket != '')
                            && (form.released_tickets != '')
                            && (parseInt(form.start_ticket) <= parseInt(form.end_ticket))
                            && noFile) ? !isDisabled : isDisabled"
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>
                            <router-link to="/collection" class="btn btn-danger btn-icon-split">
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
            this.displayLastZCorrelativeAndStartTicket();
        },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    cashier_id: null,
                    gross_amount: '',
                    card_gross_amount: '',
                    released_tickets: '',
                    start_ticket: '',
                    end_ticket: '',
                    end_ticket_number: '',
                    z_inform_number: '',
                    created_at: '',
                    error_end_bill_number_validation: '',
                    transbank_status: '',
                    transbank_status_id: 0
                },
                noFile: false,
                file: null,
                postsSelected: "",
                branch_office_posts: [],
                cashier_posts: [],
                collection_post: null,
                z_inform_number: 'N/A',
                end_ticket_number: '0'
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
                formData.append('cashier_id', this.form.cashier_id);
                formData.append('gross_amount', this.form.gross_amount);
                formData.append('card_gross_amount', this.form.card_gross_amount);
                formData.append('start_ticket', this.form.start_ticket);
                formData.append('end_ticket', this.form.end_ticket);
                formData.append('released_tickets', this.form.released_tickets);
                formData.append('z_inform_number', this.form.z_inform_number);
                formData.append('created_at', this.form.created_at);
                formData.append('file', this.file);

                axios.post('/api/collection/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCollection.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/collection');
            },
            zCorrelativeAlert() {
                if(!this.checkLastZCorrelative) {
                    this.$awn.alert("Debe mantener el correlativo de la Z", {labels: {success: "Error"}});
                }
            },
            billNumberAlert() {
                if(this.form.start_ticket > this.form.end_ticket) {
                    this.$awn.alert("El número de boleta inicial no puede ser mayor al número de la boleta final", {labels: {success: "Error"}});
                }
            },
            getCashiers() {
                axios.get('/api/cashier/multiple/'+this.form.branch_office_id+'?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.cashier_posts = response.data.data;
                });

                axios.get('/api/branch_office/'+ this.form.branch_office_id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    if(this.post.transbank_code != null) {
                        this.$set(this.form, 'transbank_status_id', 1);
                    } else {
                        this.$set(this.form, 'transbank_status_id', 0);
                    }
                });
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
            capitalizeFirstLetter(value) {
                return value.charAt(0).toUpperCase() + value.slice(1);
            },
            displayLastZCorrelativeAndStartTicket() {
                if(this.form.branch_office_id != null && this.form.cashier_id != null) {
                    axios.get('/api/collection/find/'+this.form.branch_office_id+'/'+this.form.cashier_id+'?api_token='+App.apiToken)
                    .then(response => {
                        this.collection_post = response.data.data;
                        if(this.collection_post != null)
                        {
                            this.z_inform_number = this.collection_post.z_inform_number;

                            this.end_ticket_number = this.collection_post.end_ticket;
                        } else {
                            this.z_inform_number = 'N/A';

                            this.end_ticket_number = 'N/A';
                        }
                    });
                    
                    this.z_inform_number = 'N/A';
                }
            }
        },
        computed: {
            isDisabled() {
                return true;
            },
            checkLastZCorrelative() {
                if(this.form.z_inform_number != '' && this.form.branch_office_id != null && this.form.cashier_id != null) {
                    axios.get('/api/collection/find/'+this.form.branch_office_id+'/'+this.form.cashier_id+'?api_token='+App.apiToken)
                    .then(response => {
                        this.collection_post = response.data.data;
                    });

                    if(this.collection_post != null) {
                        if(this.form.z_inform_number > this.collection_post.z_inform_number) {
                            return true;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                }
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
