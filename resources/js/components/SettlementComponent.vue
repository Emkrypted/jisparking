<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Liquidaciones
                <button v-on:click="refreshPost()" class="btn btn-warning btn-icon-split" v-if="rol_id == 1">
                    <span class="icon text-white-50">
                      <i class="fas fa-arrow-up"></i>
                    </span>
                    <span class="text">Subir</span>
                </button>
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
                            <form @submit.prevent="onSubmit" ref="searchSettlement">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">RUT</label>
                                            <input 
                                            type="text" 
                                            v-model="form.rut" 
                                            class="form-control" 
                                            v-mask="'########-#'"
                                            placeholder="Ingresa el rut del empleado"
                                            >
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Apellido</label>
                                            <input
                                            type="text" 
                                            v-model="form.father_lastname" 
                                            class="form-control"
                                            placeholder="Ingresa el apellido del empleado"
                                            >
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleFormControlSelect1">Sucursal</label>
                                            <select class="form-control" id="exampleFormControlSelect1"
                                            v-model="form.branch_office_id"
                                            >
                               .                 <option :value="null">-Seleccionar-</option>
                                                <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
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
                                        <th>RUT</th>
                                        <th>Empleado</th>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>RUT</th>
                                        <th>Empleado</th>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ post.rut }}</td>
                                        <td>{{ post.names }}</td>
                                        <td>{{ formatMonth(post.month) }}</td>
                                        <td>{{ post.year }}</td>
                                        <td>
                                            <button v-on:click="downloadSupport(post.settlement_id)" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-arrow-down"></i>
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

                if(this.form.father_lastname == '') {
                    this.form.father_lastname = null;
                }

                axios.post('/api/settlement/search/'+ this.form.rut +'/'+ this.form.father_lastname +'/'+ this.form.branch_office_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                });
            },
            getPosts() {
                if(this.form.rut == '') {
                    this.form.rut = null;
                }

                if(this.form.father_lastname == '') {
                    this.form.father_lastname = null;
                }

                if(this.form.branch_office_id == '') {
                    this.form.branch_office_id = null;
                }

                if(this.form.rut != null 
                || this.form.father_lastname != null 
                || this.form.branch_office_id != null
                ) {
                    axios.post('/api/settlement/search/'+ this.form.rut +'/'+ this.form.father_lastname +'/'+ this.form.branch_office_id +'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/settlement?page='+this.currentPage+'&api_token='+App.apiToken)
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
             forceFileDownload(response) {
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'liquidacion.pdf')
                document.body.appendChild(link)
                link.click()
            },
            downloadSupport(id) {
                axios({
                    method: 'get',
                    url: '/settlement/download/'+id,
                    responseType: 'arraybuffer',
                })
                .then((response) => {
                    this.forceFileDownload(response)
                })
                .catch((e) => console.log(e))
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    this.rol_id = response.data.data.rol_id;
                });
            },
            formatMonth(value) {
                if(value == 1) {
                    return "Enero";
                }
                if(value == 2) {
                    return "Febrero";
                }
                if(value == 3) {
                    return "Marzo";
                }
                if(value == 4) {
                    return "Abril";
                }
                if(value == 5) {
                    return "Mayo";
                }
                if(value == 6) {
                    return "Junio";
                }
                if(value == 7) {
                    return "Julio";
                }
                if(value == 8) {
                    return "Agosto";
                }
                if(value == 9) {
                    return "Septiembre";
                }
                if(value == 10) {
                    return "Octubre";
                }
                if(value == 11) {
                    return "Noviembre";
                }
                if(value == 12) {
                    return "Diciembre";
                }
            }
        },
        components: { vPagination },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    father_lastname: null,
                    rut: null
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
