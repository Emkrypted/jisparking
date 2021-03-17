<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Detalle de Pago de Facturas
            </h1>
            <hr>

            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Listado</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div v-if="rowsQuantity > 0">
                            <form @submit.prevent="onSubmit" ref="updateDte" enctype="multipart/form-data">
                                <table v-if="total > 0" class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" v-model="selectAll">
                                            </th>
                                            <th>Id</th>
                                            <th>Proveedor</th>
                                            <th>Folio</th>
                                            <th>Cuenta</th>
                                            <th>Detalle</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>
                                            </th>
                                            <th>Id</th>
                                            <th>Proveedor</th>
                                            <th>Folio</th>
                                            <th>Cuenta</th>
                                            <th>Detalle</th>
                                            <th>Monto</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr v-for="(post, index) in posts" v-bind:index="index">
                                            <td>
                                                <input type="checkbox" v-model="selected" :value="post.dte_id" number>
                                            </td>
                                            <td>{{ post.dte_id }}</td>
                                            <td>{{ post.names }}</td>
                                            <td>{{ post.folio }}</td>
                                            <td>{{ post.expense_type }}</td>
                                            <td>{{ post.comment }}</td>
                                            <td>$ {{ formatPrice(post.amount) }}</td>
                                            <td>{{ formatDate(post.created_at) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Tipo de Pago</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.payment_type_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option :value="1">Depósito</option>
                                        <option :value="2">Transferencia Electrónica</option>
                                        <option :value="3">Nota de Crédito</option>
                                    </select>
                                </div>
                               
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fecha de Pago</label>
                                    <input type="date" 
                                    
                                    class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.payment_date" 
                                    placeholder="Ingresa la fecha de pago" 
                                    required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Comentario</label>
                                    <input type="text" 
                                    
                                    class="form-control" 
                                    id="exampleInputEmail1" 
                                    v-model="form.payment_comment" 
                                    placeholder="Ingresa el comentario del pago" 
                                    required>
                                </div>
                                <button 
                                type="submit"
                                :disabled="((form.payment_type_id != null) 
                                && (form.payment_date != '')) ? !isDisabled : isDisabled"
                                class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <span class="text">Pagar</span>
                                </button>
                                <router-link to="/bill_payment" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <span class="text">Cancelar</span>
                                </router-link>
                            </form>
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
        },
        methods: {
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('selected', this.selected);
                formData.append('payment_type_id', this.form.payment_type_id);
                formData.append('payment_date', this.form.payment_date);
                formData.append('payment_comment', this.form.payment_comment);

                axios.post('/api/bill_payment/check?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.updateDte.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/bill_payment');
            },
            getPosts() {
                axios.get('/api/bill_payment/detail/'+this.$route.params.id+'?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.rowsQuantity = response.data.data.total;
                });
            },
            formatPrice(value) {
                let val = (value/1).toFixed(0).replace('.', ',')
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
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            }
        },
        components: { vPagination },
        computed: {
            isDisabled() {
                return true;
            },
            selectAll: {
                get: function () {
                    return this.posts ? this.selected.length == this.posts.length : false;
                },
                set: function (value) {
                    var selected = [];

                    if (value) {
                        this.posts.forEach(function (post) {
                            selected.push(post.dte_id);
                        });
                    }

                    this.selected = selected;
                }
            }
        },
        data: function() {
            return {
                form: {
                    payment_date: '',
                    payment_comment: '',
                    payment_type_id: null
                },
                selected: [],
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
