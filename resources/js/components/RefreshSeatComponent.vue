<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Refrescar Asientos
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="refreshSeats" enctype="multipart/form-data">
                            
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mes</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.month"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="1">Enero</option>
                                    <option :value="2">Febrero</option>
                                    <option :value="3">Marzo</option>
                                    <option :value="4">Abril</option>
                                    <option :value="5">Mayo</option>
                                    <option :value="6">Junio</option>
                                    <option :value="7">Julio</option>
                                    <option :value="8">Agosto</option>
                                    <option :value="9">Septiembre</option>
                                    <option :value="10">Octubre</option>
                                    <option :value="11">Noviembre</option>
                                    <option :value="12">Diciembre</option>

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Año</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-model="form.year"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option :value="2020">2020</option>
                                    <option :value="2021">2021</option>
                                    <option :value="2022">2022</option>
                                    <option :value="2023">2023</option>
                                    <option :value="2024">2024</option>
                                    <option :value="2025">2025</option>

                                </select>
                            </div>
                            <button
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Refrescar</span>
                            </button>

                            <router-link to="/manual_seat" class="btn btn-danger btn-icon-split">
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
        },
        data: function() {
            return {
                form: {
                    month: null,
                    year: null
                }
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
                formData.append('month', this.form.month);
                formData.append('year', this.form.year);

                axios.post('/api/seat/refresh?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.refreshSeats.reset();

                this.$router.push('/manual_seat');
            }
        },
        computed: {
            isDisabled() {
                return true;
            }
        }
    }
</script>