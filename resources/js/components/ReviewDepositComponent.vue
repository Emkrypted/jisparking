<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Revisar Depósito
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
                                <label for="exampleInputEmail1">Fecha de Recaudación</label>
                                <input type="date" 
                                class="form-control" 
                                id="exampleInputEmail1"
                                v-model="form.collection_date" 
                                placeholder="Ingresa la fecha de la recaudación" 
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Sucursal</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.branch_office_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ capitalizeFirstLetter(branch_office_post.branch_office) }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">N° de Depósito</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                v-model="form.deposit_number" placeholder="Ingresa el número del depósito" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Reacudado</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                disabled
                                v-model="form.collection_amount" placeholder="Ingresa el monto recaudado" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto Depósitado</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                v-model="form.deposit_amount" placeholder="Ingresa el monto del depósito" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Fecha de Depósito</label>
                                <input type="date" class="form-control" id="exampleInputEmail1" 
                                v-model="form.deposit_date"
                                placeholder="Ingresa la fecha de depósito" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">¿Que va a hacer con el depósito?</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.status_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="7">Aceptarlo</option>
                                    <option :value="14">Rechazarlo</option>
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

                            <router-link to="/electronic_deposit" class="btn btn-warning btn-icon-split">
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
    import moment from 'moment'

    export default {
        created() {
            this.getBranchOfficeList();
            this.getPost();
            this.getSupport();
        },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    collection_date: '',
                    deposit_number: '',
                    deposit_amount: '',
                    deposit_date: '',
                    description: '',
                    status_id: 7
                },
                image: '',
                branch_office_posts: [],
                remaining_amount: 'N/A',
                collection_total_amount: 0,
                deposit_total_amount: 0
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
            getPost() {
                axios.get('/api/deposit/'+ this.$route.params.id +'/edit?api_token='+App.apiToken)
                .then(response => {
                    this.post = response.data.data;
                    
                    this.getSupport(this.post.deposit_id);
                    this.$set(this.form, 'collection_date', moment(this.post.collection_date).format('YYYY-MM-DD'));
                    this.$set(this.form, 'branch_office_id', this.post.branch_office_id);
                    this.$set(this.form, 'deposit_number', this.post.deposit_number);
                    this.$set(this.form, 'deposit_amount', this.post.deposit_amount);
                    this.$set(this.form, 'collection_amount', this.post.collection_amount);
                    this.$set(this.form, 'deposit_date', moment(this.post.created_at).format('YYYY-MM-DD'));
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

                formData.append('collection_date', this.form.collection_date);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('deposit_number', this.form.deposit_number);
                formData.append('deposit_amount', this.form.deposit_amount);
                formData.append('deposit_date', this.form.deposit_date);
                formData.append('status_id', this.form.status_id);
                formData.append('file', this.file);
                formData.append('_method', 'PATCH')

                axios.post('/api/deposit/'+this.$route.params.id+'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.reviewDeposit.reset(); // This will clear that form

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

                this.$router.push('/electronic_deposit');
            },
            getSupport(id) {
                if(id != null) {
                    axios.get('/api/deposit/support/'+id+'?api_token='+App.apiToken)
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
            acceptDeposit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('collection_date', this.form.collection_date);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('deposit_number', this.form.deposit_number);
                formData.append('deposit_amount', this.form.deposit_amount);
                formData.append('deposit_date', this.form.deposit_date);
                formData.append('status_id', 7);
                formData.append('_method', 'PATCH')

                axios.post('/api/deposit/'+this.$route.params.id+'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

                this.$router.push('/electronic_deposit');
            },
            /**
             * Reject a collection
             *
             * @return boolean
             */
            rejectDeposit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
   
                formData.append('status_id', 14);
                formData.append('_method', 'PATCH')

                axios.post('/api/deposit/'+this.$route.params.id+'?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

                this.$router.push('/electronic_deposit');
            }
        },
        computed: {
            isDisabled() {
                return true;
            }
        }
    }
</script>