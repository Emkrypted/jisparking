<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Iva
                <router-link v-if="rol_id == 1" to="/tax/create" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Agregar</span>
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
                            <form @submit.prevent="onSubmit" ref="searchTax">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Año</label>
                                            <input type="month" class="form-control" id="exampleInputEmail1" 
                                            v-model="form.date"
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
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ formatMonth(post.month) }}</td>
                                        <td>{{ post.year }}</td>
                                        <td>
                                            <button v-on:click="downloadSupport(post.tax_id)" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            <button v-if="rol_id == 1" v-on:click="deletePost(post.tax_id, index)" class="btn btn-danger btn-circle btn-sm">
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
    
    export default {
        created() {
            this.getPosts();
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 3000);
        },
        methods: {
            onSubmit() {
                if(this.form.date == '') {
                    this.form.date = null;
                }

                axios.post('/api/tax/search/'+ this.form.date +'?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                });
            },
            getPosts() {
                if(this.form.date == '') {
                    this.form.date = null;
                }

                if(this.form.date != null
                ) {
                    axios.post('/api/tax/search/'+ this.form.date +'?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                } else {
                    axios.get('/api/tax?page='+this.currentPage+'&api_token='+App.apiToken)
                    .then(response => {
                        this.posts = response.data.data.data;
                        this.total = response.data.data.last_page;
                        this.currentPage = response.data.data.current_page;
                        this.quantity = response.data.data.total;
                        this.rowsQuantity = response.data.data.total;
                    });
                }
            },
            forceFileDownload(response) {
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', 'file.pdf')
                document.body.appendChild(link)
                link.click()
            },
            downloadSupport(id) {
                axios({
                    method: 'get',
                    url: '/tax/download/'+id,
                    responseType: 'arraybuffer',
                })
                .then((response) => {
                    this.forceFileDownload(response)
                })
                .catch((e) => console.log(e))
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    axios.delete('/api/tax/'+id+'?api_token='+App.apiToken).then(response => {
                        this.posts.splice(index, 1);

                        this.getPosts();

                        this.$awn.success("El registro ha sido borrado", {labels: {success: "Éxito"}});
                    });
                }
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
            },
        },
        components: { vPagination },
        data: function() {
            return {
                form: {
                    date: ''
                },
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
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
