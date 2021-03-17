<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Total de Recaudaciones con Tarjeta
            </h1>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <!-- Default Card Example -->
                    <div class="card mb-4">
                        <div class="card-header">
                        Buscar
                        </div>
                        <div class="card-body">
                            <form @submit.prevent="onSubmit" ref="searchTransbankCollection">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Sucursal</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.branch_office_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Periodo</label>
                                            <input type="month" class="form-control" id="exampleInputEmail1" 
                                                v-model="form.period"
                                                placeholder="Ingresa el periodo">
                                        </div>
                                    </div>
                                </div>
                                <button
                                type="submit" class="btn btn-success btn-icon-split text-right">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Buscar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div v-if="rowsQuantity > 0">
                            <form @submit.prevent="onSubmitCollectionSeat" ref="addSeat">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" v-model="selectAll">
                                            </th>
                                            <th>Sucursal</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>Sucursal</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>
                                                <input type="checkbox" v-model="selected" :value="post.collection_date +'_'+ post.amount +'_'+ post.branch_office_id" number>
                                            </td>
                                            <td>{{ post.branch_office_name }}</td>
                                            <td>$ {{ formatPrice(post.amount) }}</td>
                                            <td>{{ formatDate(post.collection_date) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button
                                type="submit" class="btn btn-success btn-icon-split text-right">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Enviar</span>
                                </button>
                            </form>
                        </div>
                        <div v-else>
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Resultado</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Resultado</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr>
                                        <td class="text-center">No hay resultados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <v-pagination v-model="currentPage" 
                            :page-count="total"
                            @input='getPosts'
                            :classes="bootstrapPaginationClasses"
                            :labels="paginationAnchorTexts"
                            ></v-pagination>

        </div>
        
    </div>
    
</template>

<script>
    import vPagination from 'vue-plain-pagination';
    import { required, minLength } from 'vuelidate/lib/validators'
    import moment from 'moment'

    export default {
        created() {
            this.getPosts();
            this.getBranchOfficeList();
        },
        methods: {
            onSubmit() {
                if(this.form.branch_office_id == '') {
                    this.form.branch_office_id = null;
                }

                if(this.form.status_id == '') {
                    this.form.status_id = null;
                }

                if(this.form.since == '') {
                    this.form.since = null;
                }

                if(this.form.until == '') {
                    this.form.until = null;
                }

                axios.get('/api/transbank_collection_accounting/search/'+ this.form.branch_office_id +'/'+ this.form.period +'?page='+this.currentPage)
                .then(response => {
                    if(response.data.data != null) {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    } else {
                        this.rowsQuantity = 0;
                        this.$awn.warning("El asiento ya ha sido creado", {labels: {success: "Error"}});
                    }
                });
            },
            onSubmitCollectionSeat(e) {
                 e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('selected', this.selected);

                axios.post('/api/transbank_collection_accounting/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.rowsQuantity = 0;

                this.selected  = false;

                this.$refs.addSeat.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});
            },
            getPosts() {
                if(this.form.branch_office_id == '') {
                    this.form.branch_office_id = null;
                }

                if(this.form.period == '') {
                    this.form.period = null;
                }

                if(this.form.branch_office_id != null 
                || this.form.period != null
                ) {
                    axios.get('/api/transbank_collection_accounting/search/'+ this.form.branch_office_id +'/'+ this.form.period +'?page='+this.currentPage)
                    .then(response => {
                        if(response.data.data != null) {
                            this.posts = response.data.data.data;
                            this.total = response.data.data.last_page;
                            this.currentPage = response.data.data.current_page;
                            this.rowsQuantity = response.data.data.total;
                        } else {
                            this.rowsQuantity = 0;

                            this.$awn.danger("El asiento ya ha sido creado", {labels: {success: "Error"}});
                        }
                    });
                } else {
                    axios.get('/api/transbank_collection_accounting?page='+this.currentPage)
                    .then(response => {
                        if(response.data.data != null) {
                            this.posts = response.data.data.data;
                            this.total = response.data.data.last_page;
                            this.currentPage = response.data.data.current_page;
                            this.rowsQuantity = response.data.data.total;
                        } else {
                            this.rowsQuantity = 0;
                        }
                    });
                }
            },
            formatPrice(value) {
                let val = (value/1).toFixed(0).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            formatDate(value) {
                return moment(value).format('DD-MM-YYYY');
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            }
        },
        components: { vPagination },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    period: ''
                },
                selected: [],
                branch_office_posts: [],
                postsSelected: "",
                posts: [],
                currentPage: 1,
                total: 0,
                rowsQuantity: '',
                bootstrapPaginationClasses: {
                    ul: 'pagination',
                    li: 'page-item',
                    liActive: 'active',
                    liDisable: 'disabled',
                    button: 'page-link'  
                },
                paginationAnchorTexts: {
                    first: 'Primera',
                    prev: '&laquo;',
                    next: '&raquo;',
                    last: 'Última'
                }
            }
        },
        validations: {
            form: {
                branch_office_id: {
                    required
                },
                created_at: {
                    required
                }
            }
        },
        computed: {
            formValid() {
                return !this.$v.$invalid;
            },
            selectAll: {
                get: function () {
                    return this.posts ? this.selected.length == this.posts.length : false;
                },
                set: function (value) {
                    var selected = [];

                    if (value) {
                        this.posts.forEach(function (post) {
                            selected.push(post.collection_date +'_'+ post.amount +'_'+ post.branch_office_id);
                        });
                    }

                    this.selected = selected;
                }
            }
        }
    }
</script>
