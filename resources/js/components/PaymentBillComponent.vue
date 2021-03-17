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
                                            <label for="exampleFormControlSelect1">Proveedor</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.branch_office_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="user_post in user_posts" :key="user_post.names" :value="user_post.rut">{{ user_post.names }}</option>
                                            </select>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Fecha</label>
                                            <input type="date" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.created_at"
                                            placeholder="Ingresa la fecha">
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
                                        <th>Id</th>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ post.dte_id }}</td>
                                        <td>{{ post.branch_office.branch_office }}</td>
                                        <td>{{ post.folio }}</td>
                                        <td>{{ post.dte_type_id }}</td>
                                        <td>
                                            <span class="badge badge-info" v-if="post.dte_version_id == 1">
                                                Emitida
                                            </span>
                                            <span class="badge badge-danger" v-if="post.dte_version_id == 2">
                                                Recibida
                                            </span>
                                        </td>
                                        <td>$ {{ post.amount }}</td>
                                        <td>{{ formatDate(post.created_at) }}</td>
                                        <td>
                                            <span class="badge badge-primary" v-if="post.status.status_id == 6">
                                                {{ post.status.status }}
                                            </span>
                                            <span class="badge badge-success" v-if="post.status.status_id == 17 && post.dte_version_id == 1">
                                                {{ post.status.status }}
                                            </span>
                                            <span class="badge badge-warning" v-if="post.status.status_id == 18 && post.dte_version_id == 2">
                                                {{ post.status.status }}
                                            </span>
                                            <span class="badge badge-success" v-if="post.status.status_id == 19 && post.dte_version_id == 2">
                                                {{ post.status.status }}
                                            </span>
                                        </td>
                                        <td>
                                            <router-link v-if="post.status.status_id == 6" :to="`/dte/impute/${post.dte_id}`" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-check"></i>
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
            this.getBranchOfficeList();
            this.getRol();
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 3000);
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

                axios.post('/api/dte/search/'+ this.form.branch_office_id +'/'+ this.form.status_id +'/'+ this.form.since +'/'+this.form.until+'?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                });
            },
            getPosts() {
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

                if(this.form.branch_office_id != null 
                || this.form.status_id != null 
                || this.form.since != null 
                || this.form.until != null
                ) {
                    axios.get('/api/dte/search/'+ this.form.branch_office_id +'/'+ this.form.status_id +'/'+ this.form.since +'/'+this.form.until+'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/dte?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.rowsQuantity = response.data.data.total;
                    });
                }
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    axios.delete('/api/dte/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                        this.getPosts();
                        this.$awn.success("El registro ha sido borrado", {labels: {success: "Éxito"}});
                    });
                }
            },
            formatPrice(value) {
                let val = (value/1).toFixed(2).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            formatDate(value) {
                return moment(value).format('DD-MM-YYYY');
            },
            getSuppliers() {
                axios.get('/api/supplier?api_token='+App.apiToken)
                .then(response => {
                    this.user_posts = response.data.data;
                });
            },
            refreshPost() {
                axios.post('/api/dte/refresh?api_token='+App.apiToken)
                .then(response => {
                    console.log(1);
                });

                axios.get('/api/dte?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.rowsQuantity = response.data.data.total;
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
                    status_id: null
                },
                branch_office_posts: [],
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
