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
                        <form @submit.prevent="onSubmit" ref="createCustomer" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="exampleInputEmail1">RUT</label>
                                <input v-on:blur="validateRut" 
                                type="text" 
                                v-model="form.rut" 
                                class="form-control" 
                                v-mask="'########-N'"
                                placeholder="Ingresa el rut del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.names"
                                placeholder="Ingresa el nombre del cliente">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Correo</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.email"
                                placeholder="Ingresa el correo del cliente">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Región</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.region_id"
                                v-on:change="getCommuneList"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="region_post in region_posts" :key="region_post.region_id" :value="region_post.region_id">{{ region_post.region }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Comuna</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.commune_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-for="commune_post in commune_posts" :key="commune_post.commune_id" :value="commune_post.commune_id">{{ commune_post.commune }}</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Dirección</label>
                                <input
                                type="text" 
                                v-model="form.address" 
                                class="form-control"
                                placeholder="Ingresa la dirección del cliente"
                                >
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Actividad</label>
                                <input
                                type="text" 
                                v-model="form.activity" 
                                class="form-control"
                                placeholder="Ingresa la actividad del cliente"
                                >
                            </div>
                            <button
                            :disabled="((validateRut)) ? !isDisabled : isDisabled"
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
    import { required, minLength } from 'vuelidate/lib/validators';

    export default {
        created() {
            this.getBranchOfficeList();
            this.getRegionList();
            this.getBranchOfficeList();
        },
        data: function() {
            return {
                form: {
                    rut: '',
                    names: '',
                    email: '',
                    region_id: null,
                    commune_id: null,
                    address: '',
                    activity: ''
                },
                commune_posts: [],
                region_posts: []
            }
        },
        methods: {
            validateRut(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('rut', this.form.rut);

                axios.post('/api/rut?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    response = response.data;
                    response = response.split('_');
                    if(response[0] == 2) {
                        alert("El RUT ingresado es incorrecto. El digito verificador es: "+response[1]);
                        return false;
                    } else {
                        return true;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('rut', this.form.rut);
                formData.append('names', this.form.names);
                formData.append('email', this.form.email);
                formData.append('region_id', this.form.region_id);
                formData.append('commune_id', this.form.commune_id);
                formData.append('address', this.form.address);
                formData.append('activity', this.form.activity);

                axios.post('/api/customer/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCustomer.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/customer');
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            getRegionList() {
                axios.get('/api/region?api_token='+App.apiToken)
                .then(response => {
                    console.log(response.data.data);
                    this.region_posts = response.data.data;
                });
            },
            getCommuneList() {
                axios.get('/api/commune/search/region/'+ this.form.region_id +'?api_token='+App.apiToken)
                .then(response => {
                    console.log(response.data.data);
                    this.commune_posts = response.data.data;
                });
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
