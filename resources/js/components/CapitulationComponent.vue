<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Rendiciones
                <router-link to="/capitulation/create" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Crear</span>
                </router-link>
            </h1>
            <hr>
            <div class="row" v-if="rol_id == 1">
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
                                            <label for="exampleFormControlSelect1">Supervisor</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.supervisor_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="supervisor_post in supervisor_posts" :key="supervisor_post.names" :value="supervisor_post.rut">{{ supervisor_post.names }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Tipo de Cuenta</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.expense_type_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option v-for="expense_type_post in expense_type_posts" :key="expense_type_post.expense_type_id" :value="expense_type_post.expense_type_id">{{ expense_type_post.expense_type }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Desde</label>
                                            <input type="date" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.since"
                                            placeholder="Ingresa la fecha desde">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Hasta</label>
                                            <input type="date" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.until"
                                            placeholder="Ingresa la fecha hasta">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Estatus</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.status_id"
                                            >
                                                <option :value="null">-Seleccionar-</option>
                                                <option :value="11">Es espera</option>
                                                <option :value="17">Imputada</option>
                                                <option :value="14">Rechazada</option>
                                                <option :value="7">Revisada</option>
                                            </select>
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
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div v-if="rol_id != 11">
                            <div v-if="rowsQuantity > 0">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Colaborador</th>
                                            <th>Tipo</th>
                                            <th>Categoría</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Fecha Doc</th>
                                            <th>Estatus</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Id</th>
                                            <th>Colaborador</th>
                                            <th>Tipo</th>
                                            <th>Categoría</th>
                                            <th>Descripción</th>
                                            <th>Monto</th>
                                            <th>Fecha Doc</th>
                                            <th>Estatus</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>{{ post.capitulation_id }}</td>
                                            <td>{{ post.names }}</td>
                                            <td>
                                                <span v-if="post.capitulation_type_id == 1">
                                                    Fondo por Rendir
                                                </span>
                                                <span v-if="post.capitulation_type_id == 2">
                                                    Rendición de Gastos
                                                </span>
                                            </td>
                                            <td>{{ post.expense_type }}</td>
                                            <td>{{ post.description }}</td>
                                            <td>$ {{ formatPrice(post.amount) }}</td>
                                            <td>{{ formatDate(post.document_date) }}</td>
                                            <td>
                                                <span class="badge badge-primary" v-if="post.status_id == 11">
                                                    {{ post.status }}
                                                </span>
                                                <span class="badge badge-warning" v-if="post.status_id == 7">
                                                    {{ post.status }}
                                                </span>
                                                <span class="badge badge-danger" v-if="post.status_id == 14">
                                                    {{ post.status }}
                                                </span>
                                                <span class="badge badge-success" v-if="post.status_id == 17">
                                                    {{ post.status }}
                                                </span>
                                            </td>
                                            <td>
                                                <router-link v-if="rol_id == 1 && post.status_id == 11" :to="`/capitulation/review/${post.capitulation_id}`"  class="btn btn-warning btn-circle btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </router-link>
                                                <button v-if="rol_id == 1" v-on:click="deletePost(post.capitulation_id, index)" class="btn btn-danger btn-circle btn-sm">
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
                        <div v-if="rol_id == 11">
                            <div v-if="rowsQuantity > 0">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>RUT</th>
                                            <th>Colaborador</th>
                                            <th>Monto</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>RUT</th>
                                            <th>Colaborador</th>
                                            <th>Monto</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>{{ post.rut }}</td>
                                            <td>{{ post.names }}</td>
                                            <td>$ {{ formatPrice(post.amount) }}</td>
                                            <td>
                                                <router-link v-if="rol_id == 11" :to="`/capitulation/pay/${post.rut}`" class="btn btn-danger btn-circle btn-sm">
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
            this.getSupervisorList();
            this.getExpenseTypeList();
            this.getRol();
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 3000);
        },
        methods: {
            onSubmit() {
                if(this.form.supervisor_id == '') {
                    this.form.supervisor_id = null;
                }

                if(this.form.expense_type_id == '') {
                    this.form.expense_type_id = null;
                }

                if(this.form.since == '') {
                    this.form.since = null;
                }

                if(this.form.until == '') {
                    this.form.until = null;
                }

                if(this.form.status_id == '') {
                    this.form.status_id = null;
                }

                axios.get('/api/capitulation/search/'+ this.form.supervisor_id +'/'+ this.form.expense_type_id +'/'+ this.form.since +'/'+ this.form.until +'/'+ this.form.status_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                });
            },
            getExpenseTypeList() {
                axios.get('/api/expense_type?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
                });
            },
            getPosts() {
                if(this.form.supervisor_id == '') {
                    this.form.supervisor_id = null;
                }

                if(this.form.expense_type_id == '') {
                    this.form.expense_type_id = null;
                }

                if(this.form.since == '') {
                    this.form.since = null;
                }

                if(this.form.until == '') {
                    this.form.until = null;
                }

                if(this.form.status_id == '') {
                    this.form.status_id = null;
                }

                if(this.form.supervisor_id != null 
                || this.form.expense_type_id != null 
                || this.form.since != null 
                || this.form.until != null
                || this.form.status_id != null
                ) {
                    axios.get('/api/capitulation/search/'+ this.form.supervisor_id +'/'+ this.form.expense_type_id +'/'+ this.form.since +'/'+this.form.until+'/'+this.form.status_id+'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/capitulation?page='+this.currentPage+'&api_token='+App.apiToken)
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
                    axios.delete('/api/capitulation/'+id+'?api_token='+App.apiToken).then(response => {
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
            getSupervisorList() {
                axios.get('/api/supervisor?api_token='+App.apiToken)
                .then(response => {
                    this.supervisor_posts = response.data.data;
                });
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            },
            payCapitulation(id, index) {
                if(confirm("¿Realmente usted quiere pagar la rendición?")) {
                    axios.get('/api/capitulation/pay/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                        this.getPosts();
                        this.$awn.success("La rendición ha sido pagada", {labels: {success: "Éxito"}});
                    });
                }
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    axios.delete('/api/capitulation/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);
                        this.getPosts();
                        this.$awn.success("El registro ha sido borrado", {labels: {success: "Éxito"}});
                    });
                }
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
                    expense_type_id: null,
                    supervisor_id: null,
                    status_id: null
                },
                branch_office_posts: [],
                supervisor_posts: [],
                expense_type_posts: [],
                postsSelected: "",
                rol_id: this.rol_id,
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
