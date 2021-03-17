<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Pago de Facturas
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
                            <form @submit.prevent="onSubmit" ref="searchDTE">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Folio</label>
                                            <input type="number" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.folio"
                                            placeholder="Ingresa el folio">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">RUT</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.rut"
                                            v-mask="'########-#'"
                                            placeholder="Ingresa el RUT">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Proveedor</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.supplier_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="user_post in user_posts" :key="user_post.names" :value="user_post.rut">{{ user_post.names }}</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                </div>
                                <button
                                type="submit" class="btn btn-success btn-icon-split text-right">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-search"></i>
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
                            <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ post.names }}</td>
                                        <td>$ {{ formatPrice(post.amount) }}</td>
                                        <td>
                                            <router-link :to="`/bill_payment/review/${post.rut}`"  class="btn btn-warning btn-circle btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </router-link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    import moment from 'moment'

    export default {
        created() {
            this.getPosts();
            this.getRol();
            this.getSupplierList();
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 3000);
        },
        methods: {
            onSubmit() {
                if(this.form.supplier_id == '') {
                    this.form.supplier_id = null;
                }

                if(this.form.folio == '') {
                    this.form.folio = null;
                }

                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.supplier_id != null 
                || this.form.folio != null
                || this.form.rut != null
                ) {
                    axios.post('/api/bill_payment/search/'+ this.form.supplier_id +'/'+this.form.folio+'/'+this.form.rut+'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                }
            },
            getPosts() {
                if(this.form.supplier_id == '') {
                    this.form.supplier_id = null;
                }

                if(this.form.folio == '') {
                    this.form.folio = null;
                }

                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.supplier_id != null 
                || this.form.folio != null
                || this.form.rut != null
                ) {
                    axios.post('/api/bill_payment/search/'+ this.form.supplier_id +'/'+this.form.folio+'/'+this.form.rut+'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/bill_payment?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.rowsQuantity = response.data.data.total;
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
            getSupplierList() {
                axios.get('/api/supplier?api_token='+App.apiToken)
                .then(response => {
                    this.user_posts = response.data.data;
                });
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            }
        },
        components: { vPagination },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    since: null,
                    until: null,
                    status_id: null,
                    supplier_id: null,
                    rut: '',
                    folio: ''
                },
                user_posts: [],
                rol_id: this.rol_id,
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
        }
    }
</script>
