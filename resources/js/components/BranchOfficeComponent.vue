<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Sucursales
                <router-link to="/branch_office/create" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-check"></i>
                    </span>
                    <span class="text">Agregar</span>
                </router-link>
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Sucursal</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Id</th>
                                    <th>Sucursal</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr v-for="(post, index) in posts" v-bind:index="index">
                                    <td>{{ post.branch_office_id }}</td>
                                    <td>{{ post.branch_office }}</td>
                                    <td>{{ formatDate(post.created_at) }}</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-circle btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button v-on:click="deletePost(post.branch_office_id, index)" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
            setTimeout(function () {
                console.log('success');
		        this.getPosts();
            }.bind(this), 3000);
        },
        methods: {
            getPosts() {
                axios.get('/branch_office?page='+this.currentPage)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                });
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    axios.delete('/api/branch_office/'+id).then(response => {
                        this.posts.splice(index, 1);

                        this.getPosts();

                        this.$awn.success("El registro ha sido borrado", {labels: {success: "Éxito"}});
                    });
                }
            },
            formatDate(value) {
                return moment(value).format('DD-MM-YYYY');
            }
        },
        components: { vPagination },
        data: function() {
            return {
                user_rut: '',
                postsSelected: "",
                posts: [],
                currentPage: 1,
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
        }
    }
</script>
