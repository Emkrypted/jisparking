<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Ver Recaudación
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
                        <form @submit.prevent="onSubmit" ref="reviewDeposit" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Id</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.collection_id"
                                disabled>
                            </div>
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
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="cashier_post in cashier_posts" :key="cashier_post.cashier_id" :value="cashier_post.cashier_id">{{ cashier_post.cashier }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">N° Informe Z</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.z_inform_number"
                                v-on:blur="zCorrelativeAlert"
                                placeholder="Ingresa el número de informe z">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.gross_amount"
                                placeholder="Ingresa el monto bruto">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Bruto en Tarjeta</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.card_gross_amount"
                                placeholder="Ingresa el monto bruto en tarjeta">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Boleta Inicial</strong></label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.start_ticket"
                                v-on:blur="billNumberAlert"
                                placeholder="Ingresa el número de boleta inicial">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Boleta Final</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.end_ticket"
                                v-on:blur="billNumberAlert"
                                placeholder="Ingresa el número de boleta final">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tickets Liberados</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.released_tickets"
                                placeholder="Ingresa la cantidad de tickets liberados">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tickets Pagados</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.ticket_number"
                                placeholder="Ingresa la cantidad de tickets pagados">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Comentario (Si la va a rechazar)</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.comment"
                                value="La foto no corresponde o no se ve de forma correcta."
                                placeholder="Ingresa el comentario si la va a rechazar">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">¿Que va a hacer con la recaudación?</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.status_id"
                                    >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="21">Aceptarla</option>
                                    <option :value="22">Rechazarla</option>
                                </select>
                            </div>
                            
                            <button
                                class="btn btn-success btn-icon-split"
                                >
                                <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Enviar</span>
                            </button>

                            <router-link to="/collection" class="btn btn-warning btn-icon-split">
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
    import { required, minLength } from 'vuelidate/lib/validators'
    import moment from 'moment'

    export default {
        created() {
            this.getBranchOfficeList();
            this.getCashierList();
            this.getPost();
            this.getSupport();
        },
        data: function() {
            return {
                form: {
                    collection_id: '',
                    branch_office_id: null,
                    cashier_id: null,
                    gross_amount: '',
                    comment: 'La foto no corresponde o no se ve de forma correcta.',
                    released_tickets: '',
                    start_ticket: '',
                    end_ticket: '',
                    z_inform_number: '',
                    end_ticket_number: '',
                    created_at: '',
                    error_end_bill_number_validation: '',
                    ticket_number: '',
                    status_id: 21,
                    card_gross_amount: 0
                },
                image: '',
                postsSelected: "",
                branch_office_posts: [],
                cashier_posts: [],
                z_inform_number: 'N/A',
                end_ticket_number: '0'
            }
        },
        methods: {
            getPost() {
                axios.get('/api/collection/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    this.getSupport(this.post.collection_id);
                    this.$set(this.form, 'collection_id', this.post.collection_id);
                    this.$set(this.form, 'created_at', moment(this.post.created_at).format('YYYY-MM-DD'));
                    this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                    this.$set(this.form, 'cashier_id', this.post.cashier_id);
                    this.$set(this.form, 'start_ticket', this.post.start_ticket);
                    this.$set(this.form, 'end_ticket', this.post.end_ticket);
                    this.$set(this.form, 'z_inform_number', this.post.z_inform_number);
                    this.$set(this.form, 'gross_amount', this.post.gross_amount);
                    this.$set(this.form, 'released_tickets', this.post.released_tickets);
                    this.$set(this.form, 'collection_id', this.post.collection_id);
                    this.$set(this.form, 'ticket_number', this.post.ticket_number);
                    if(this.post.card_gross_amount != null) {
                        this.$set(this.form, 'card_gross_amount', this.post.card_gross_amount);
                    } else {
                        this.$set(this.form, 'card_gross_amount', 0);
                    }
                });
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
            },
            /**
             * Get a list of branch offices
             *
             * @return Object
             */
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            /**
             * Get a list of cashiers
             *
             * @return Object
             */
            getCashierList() {
                axios.get('/cashier/1')
                .then(response => {
                    this.cashier_posts = response.data.data;
                });
            },
            /**
             * Load the file variable
             *
             * @return boolean
             */
            onFileChange(e){
                console.log(e.target.files[0]);
                this.file = e.target.files[0];
            },
            /**
             * Capitalize the first word in any string
             *
             * @return string
             */
            capitalizeFirstLetter(value) {
                return value.charAt(0).toUpperCase() + value.slice(1);
            },
            /**
             * Get support image
             *
             * @return string
             */
            getSupport(id) {
                if(id != null) {
                    axios.get('/api/collection/support/'+id+'?api_token='+App.apiToken)
                    .then(response => {
                        this.image = response.data.data;
                    });
                }
            },
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
                formData.append('start_ticket', this.form.start_ticket);
                formData.append('end_ticket', this.form.end_ticket);
                formData.append('released_tickets', this.form.released_tickets);
                formData.append('z_inform_number', this.form.z_inform_number);
                formData.append('created_at', this.form.created_at);
                formData.append('status_id', this.form.status_id);
                formData.append('ticket_number', this.form.ticket_number);
                formData.append('card_gross_amount', this.form.card_gross_amount);
                formData.append('_method', 'PATCH')

                axios.post('/api/collection/'+this.$route.params.id+'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

                this.$router.push('/collection');
            }
        },
        computed: {
            /**
             * Enable and disabled a input, button, etc
             *
             * @return boolean
             */
            isDisabled() {
                return true;
            },
            /**
             * Check the last Z correlative in the database
             *
             * @return integer
             */
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
                        return true;
                    }
                } else {
                    return false;
                }
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
