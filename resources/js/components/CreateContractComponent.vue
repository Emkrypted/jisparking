<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Contrato
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createContract" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label for="exampleInputEmail1">RUT</label>
                                <input type="text" 
                                v-model="form.rut" 
                                class="form-control" 
                                v-mask="'########-#'"
                                placeholder="Ingresa el rut de la empresa"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mandante</label>
                                <input type="text" 
                                v-model="form.boss" 
                                class="form-control"
                                placeholder="Ingresa el nombre del mandante"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Fecha de Inicio de Contrato</label>
                                <input type="date" class="form-control" v-model="form.created_at">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Fecha de Renovación de Contrato</label>
                                <input type="date" class="form-control" v-model="form.renewal_date">
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
                                <label for="exampleInputEmail1">Dirección</label>
                                <input
                                type="text"
                                v-model="form.address" 
                                class="form-control"
                                placeholder="Ingresa la dirección"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Duración (Meses)</label>
                                <input
                                type="number" 
                                v-model="form.duration" 
                                class="form-control"
                                placeholder="Ingresa la duración"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">UF o Pesos</label>
                                <input
                                type="text"
                                v-model="form.uf" 
                                class="form-control"
                                placeholder="Ingresa el valor"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Soporte</label>
                                <input ref="file" accept="application/pdf" type="file" class="form-control" v-on:change="onFileChange" required>
                            </div>
                            <button
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>

                            <router-link to="/contract" class="btn btn-danger btn-icon-split">
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
        },
        methods: {
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
                formData.append('rut', this.form.rut);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('duration', this.form.duration);
                formData.append('uf', this.form.uf);
                formData.append('created_at', this.form.created_at);
                formData.append('boss', this.form.boss);
                formData.append('renewal_date', this.form.renewal_date);
                formData.append('address', this.form.address);
                formData.append('file', this.file);

                axios.post('/api/contract/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createContract.reset();

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/contract');
            }
        },
        data: function() {
            return {
                form: {
                    rut: '',
                    branch_office_id: null,
                    duration: '',
                    uf: '',
                    created_at: '',
                    boss: '',
                    renewal_date: '',
                    address: ''
                },
                date_less_five: '',
                noFile: false,
                branch_office_posts: [],
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