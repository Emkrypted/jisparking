<template>
    <div>

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
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Mes</th>
                                        <th>Año</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>{{ post.tax_id }}</td>
                                        <td>{{ post.month }}</td>
                                        <td>{{ post.year }}</td>
                                        <td>
                                            <button v-on:click="downloadSupport(post.tax_id)" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-arrow-down"></i>
                                            </button>
                                            <button v-on:click="deletePost(post.tax_id, index)" class="btn btn-danger btn-circle btn-sm">
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
    
</template>

<script>
    import vPagination from 'vue-plain-pagination';

    export default {
        props: ['username'],
        methods: {
            getPosts() {
                axios.get('/api/tax?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                });

                this.$forceUpdate()
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
            }
        },
        components: { vPagination },
        data: function() {
            return {
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
