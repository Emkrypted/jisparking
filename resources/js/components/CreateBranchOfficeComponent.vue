<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Sucursal
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createBranchOffice">
                            
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.name"
                                placeholder="Ingresa el nombre de la sucursal">
                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Comuna</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.commune_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="commune_post in commune_posts" :key="commune_post.commune_id" :value="commune_post.commune_id">{{ capitalizeFirstLetter(commune_post.commune) }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Dirección</label>
                                <textarea class="form-control" id="exampleInputEmail1" 
                                v-model="form.address"
                                placeholder="Ingresa la dirección de la sucursal">
                                </textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Código del DTE</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.dte_code"
                                placeholder="Ingresa el código del dte">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Precio por Minuto</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.price_minute"
                                placeholder="Ingresa el precio por minuto">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Plazas</label>
                                <input type="number" class="form-control" id="exampleInputEmail1" 
                                v-model="form.spots"
                                placeholder="Ingresa la cantidad de plazas">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Cantidad Folios</label>
                                <input type="number" class="form-control" id="exampleInputEmail1"
                                v-model="form.folios_number"
                                placeholder="Ingresa la cantidad de folios">
                            </div>
                            <button 
                            :disabled="((form.name != '') 
                            && (form.address != null) 
                            && (form.dte_code != '') 
                            && (form.price_minute != '') 
                            && (form.spots != '')
                            && (form.folios_number != '')) ? !isDisabled : isDisabled"
                            type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>

                            <router-link to="/branch_office" class="btn btn-danger btn-icon-split">
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
            this.getCommuneList();
        },
        data: function() {
            return {
                form: {
                    name: "",
                    commune_id: null,
                    address: "",
                    dte_code: "",
                    price_minute: "",
                    spots: "",
                    folios_number: ""
                },
                commune_posts: [],
            }
        },
        methods: {
            onSubmit() {
                axios.post('/api/branch_office/store?api_token='+App.apiToken, {
                    name: this.form.name,
                    address: this.form.address,
                    dte_code: this.form.dte_code,
                    price_minute: this.form.price_minute,
                    spots: this.form.spots,
                    start_correlative: this.form.folios_number
                })
                .then(function (response) {
                    console.log('enviado');
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createBranchOffice.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/branch_office');
            },
            getCommuneList() {
                axios.get('/api/commune')
                .then(response => {
                    this.commune_posts = response.data.data;
                });
            },
            capitalizeFirstLetter(value) {
                return value.charAt(0).toUpperCase() + value.slice(1);
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