<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Facturas
                <router-link to="/bill/create" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Emitir</span>
                </router-link>
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
                            <form @submit.prevent="onSubmit" ref="searchCollection">
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
                                            <label for="exampleInputEmail1">RUT</label>
                                            <input type="text" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.rut"
                                            placeholder="Ingresa el RUT">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Estatus</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.status_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option value="24">Aceptada</option>
                                                <option value="7">En espera</option>
                                                <option value="14">Rechazada</option>
                                            </select>
                                        </div>
                                        
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Supervisor</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.supervisor_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="supervisor_post in supervisor_posts" :key="supervisor_post.names" :value="supervisor_post.rut">{{ supervisor_post.names }}</option>
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
                                        <th>Id</th>
                                        <th>RUT</th>
                                        <th>Cliente/Proveedor</th>
                                        <th>Sucursal</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Estatus</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>RUT</th>
                                        <th>Cliente/Proveedor</th>
                                        <th>Sucursal</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Estatus</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ post.dte_id }}</td>
                                        <td>{{ post.rut }}</td>
                                        <td>{{ post.names }}</td>
                                        <td>{{ post.branch_office }}</td>
                                        <td>$ {{ formatPrice(post.amount) }}</td>
                                        <td>{{ formatDate(post.created_at) }}</td>
                                        <td>
                                            <span class="badge badge-success" v-if="post.status_id == 6">
                                                {{ post.status }}
                                            </span>
                                            <span class="badge badge-primary" v-if="post.status_id == 18">
                                                {{ post.status }}
                                            </span>
                                            <span class="badge badge-warning" v-if="post.status_id == 24">
                                                {{ post.status }}
                                            </span>
                                            <span class="badge badge-danger" v-if="post.status_id == 14">
                                                {{ post.status }}
                                            </span>
                                        </td>
                                        <td>
                                            <button v-if="rol_id == 4 && post.status_id == 6" v-on:click="acceptTicket(post.dte_id, index)" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button v-if="rol_id == 1 && post.status_id == 24" v-on:click="acceptTicket(post.dte_id, index)" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button v-if="rol_id == 1 && post.status_id == 24" v-on:click="rejectTicket(post.dte_id, index)" class="btn btn-danger btn-circle btn-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <router-link v-if="post.status_id == 6" :to="`/bill/edit/${post.dte_id}`" class="btn btn-primary btn-circle btn-sm">
                                                <i class="fas fa-edit"></i> 
                                            </router-link>
                                            <button v-if="post.status_id == 6" v-on:click="deletePost(post.dte_id, index)" class="btn btn-danger btn-circle btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
        mounted() {
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 3000);
        },
        created() {
            this.getPosts();
            this.getBranchOfficeList();
            this.getSupervisorList();
            this.getRol();
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 7000);
        },
        methods: {
            onSubmit() {
                if(this.form.branch_office_id == '') {
                    this.form.branch_office_id = null;
                }

                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.status_id == '') {
                    this.form.status_id = null;
                }

                if(this.form.supervisor_id == '') {
                    this.form.supervisor_id = null;
                }

                axios.post('/api/bill/search/'+ this.form.branch_office_id +'/'+ this.form.rut +'/'+ this.form.status_id +'/'+ this.form.supervisor_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
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

                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.status_id == '') {
                    this.form.status_id = null;
                }

                if(this.form.supervisor_id == '') {
                    this.form.supervisor_id = null;
                }

                if(this.form.branch_office_id != null 
                || this.form.rut != null
                || this.form.status_id != null
                ) {
                    axios.post('/api/bill/search/'+ this.form.branch_office_id +'/'+ this.form.rut +'/'+ this.form.status_id +'/'+ this.form.supervisor_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/bill?page='+this.currentPage+'&api_token='+App.apiToken)
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
            },
            refreshPost() {
                axios.post('/api/dte/refresh?api_token='+App.apiToken)
                .then(response => {
                    this.getPosts();
                });
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            },
            acceptTicket(value, index) {
                axios.get('/api/dte/accept/'+ value +'?api_token='+App.apiToken)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.posts.splice(index, 1);
            },
            rejectTicket(value) {
                axios.get('/api/dte/reject/'+ value +'?api_token='+App.apiToken)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.getPosts();
            },
            getSupervisorList() {
                axios.get('/api/supervisor?api_token='+App.apiToken)
                .then(response => {
                    this.supervisor_posts = response.data.data;
                });
            }
        },
        components: { vPagination },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    status_id: null,
                    rut: '',
                    supervisor_id: null
                },
                branch_office_posts: [],
                supervisor_posts: [],
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
