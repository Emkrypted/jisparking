<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Recaudación Electrónica
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
                                            <label for="exampleInputEmail1">Fecha</label>
                                            <input type="date" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.created_at"
                                            placeholder="Ingresa la fecha de la recaudación">
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
                            <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Sucursal</th>
                                        <th>Caja</th>
                                        <th>Monto Bruto</th>
                                        <th>Monto Neto</th>
                                        <th>Estatus</th>
                                        <th>Fecha Recaudación</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Sucursal</th>
                                        <th>Caja</th>
                                        <th>Monto Bruto</th>
                                        <th>Monto Neto</th>
                                        <th>Estatus</th>
                                        <th>Fecha Recaudación</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ post.collection_id }}</td>
                                        <td>{{ post.branch_office.branch_office }}</td>
                                        <td>{{ post.cashier.cashier }}</td>
                                        <td>$ {{ formatPrice(post.gross_amount)}}</td>
                                        <td>$ {{ formatPrice(post.net_amount) }}</td>
                                        <td>
                                            <span class="badge badge-primary" v-if="post.status.status_id == 11">
                                                {{ post.status.status }}
                                            </span>
                                            <span class="badge badge-success" v-if="post.status.status_id == 7">
                                                {{ post.status.status }}
                                            </span>
                                            <span class="badge badge-danger" v-if="post.status.status_id == 8">
                                                {{ post.status.status }}
                                            </span>
                                        </td>
                                        <td>{{ formatDate(post.created_at) }}</td>
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
    import { required, minLength } from 'vuelidate/lib/validators'
    import moment from 'moment'

    export default {
        created() {
            this.getPosts();
            this.getBranchOfficeList();
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

                if(this.form.created_at == '') {
                    this.form.created_at = null;
                }

                axios.get('/api/electronic_collection/search/'+ this.form.branch_office_id +'/'+ this.form.created_at +'?page='+this.currentPage+'&api_token='+App.apiToken)
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

                if(this.form.created_at == '') {
                    this.form.created_at = null;
                }

                if(this.form.branch_office_id  != null 
                || this.form.status_id != null 
                || this.form.created_at != null
                ) {
                    axios.get('/api/electronic_collection/search/'+ this.form.branch_office_id +'/'+ this.form.created_at +'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/electronic_collection?page='+this.currentPage+'&api_token='+App.apiToken)
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
                    axios.delete('/api/electronic_collection/'+id+'?api_token='+App.apiToken).then(response => {
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
            }
        },
        components: { vPagination },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    status_id: null,
                    created_at: ''
                },
                branch_office_posts: [],
                postsSelected: '',
                posts: [],
                currentPage: 1,
                rowsQuantity: '',
                total: 0,
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
                status_id: {
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
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
