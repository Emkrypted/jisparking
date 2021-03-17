<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Clientes
                <router-link to="/customer/create" class="btn btn-success btn-icon-split">
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
                                    <th>RUT</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>RUT</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Fecha</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <tr v-for="(post, index) in posts" v-bind:index="index">
                                    <td>{{ post.rut }}</td>
                                    <td>{{ post.names }}</td>
                                    <td>{{ post.email }}</td>
                                    <td>{{ post.created_at }}</td>
                                    <td>
                                        <router-link :to="`/costumer/edit/${post.rut}`" class="btn btn-info btn-circle btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </router-link>

                                        <button v-on:click="deletePost(post.rut, index)" class="btn btn-danger btn-circle btn-sm">
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
                axios.get('/customer?page='+this.currentPage)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                });
            },
            deletePost(id, index) {
                if(confirm("¿Realmente usted quiere borrar el registro?")) {
                    axios.delete('/api/customer/'+id).then(response => {
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
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
