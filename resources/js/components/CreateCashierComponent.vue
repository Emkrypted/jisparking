<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Caja
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createCashier">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.cashier"
                                placeholder="Ingresa el nombre de la caja">
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
                            <div class="form-group">
                                <label for="exampleInputEmail1">N° de Serie de la Impresora</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.serie"
                                placeholder="Ingresa el número de serie">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">N° de Serie del Computador</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.computer_serie"
                                placeholder="Ingresa el número de serie del computador">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Tipo de Caja</label>
                                <select class="form-control" 
                                v-model="form.cashier_type_id"
                                id="exampleFormControlSelect1">
                                    <option :value="null">- Seleccione -</option>
                                    <option value="2">Automática</option>
                                    <option value="3">Electrónica</option>
                                    <option value="1">Manual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Anydesk Id</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.anydesk_id"
                                placeholder="Ingresa el Id del Anydesk">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Transbank Id</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.transbank_id"
                                placeholder="Ingresa el Id del Transbank">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Estatus</label>
                                <select class="form-control" 
                                v-model="form.status_id"
                                placeholder="Ingresa el estatus de la caja"
                                id="exampleFormControlSelect1">
                                    <option :value="null">- Seleccionar -</option>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                            <button 
                            :disabled="!formValid"
                            type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>

                            <router-link to="/cashier" class="btn btn-danger btn-icon-split">
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

    export default {
        created() {
            this.getBranchOfficeList();
        },
        data: function() {
            return {
                form: {
                    cashier: "",
                    branch_office_id: null,
                    serie: "",
                    computer_serie: "",
                    cashier_type_id: null,
                    anydesk_id: "",
                    transbank_id: "",
                    status_id: null
                }
            }
        },
        methods: {
            onSubmit() {
                axios.post('/api/cashier/store?api_token='+App.apiToken, {
                    cashier: this.$v.form.cashier.$model,
                    serie: this.$v.form.serie.$model,
                    cashier_type_id: this.$v.form.cashier_type_id.$model,
                    status_id: this.$v.form.status_id.$model
                })
                .then(function (response) {
                    console.log('enviado');
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCashier.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/cashier');
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
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
