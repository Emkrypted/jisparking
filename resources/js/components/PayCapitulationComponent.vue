<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Pagar Rendición
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="imputeCollection" enctype="multipart/form-data">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" v-model="selectAll">
                                        </th>
                                        <th>Id</th>
                                        <th>Colaborador</th>
                                        <th>Tipo</th>
                                        <th>Categoría</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Fecha Doc</th>
                                        <th>Fecha Rend</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>
                                            <input type="checkbox" v-model="selectAll">
                                        </th>
                                        <th>Id</th>
                                        <th>Colaborador</th>
                                        <th>Tipo</th>
                                        <th>Categoría</th>
                                        <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Fecha Doc</th>
                                        <th>Fecha Rend</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <tr v-for="(post, index) in posts" v-bind:index="index">
                                        <td>
                                            <input type="checkbox" v-model="selected" :value="post.capitulation_id" number>
                                        </td>
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
                                            {{ formatDate(post.created_at) }}
                                        </td>
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
                            :disabled="((form.payment_type_id != '')
                            && (form.payment_date != '')
                            && (form.payment_comment != '')) ? !isDisabled : isDisabled"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Pagar</span>
                            </button>
                            <router-link to="/capitulation" class="btn btn-danger btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-times"></i>
                                </span>
                                <span class="text">Cancelar</span>
                            </router-link>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
    import moment from 'moment'

    export default {
        created() {
            this.getExpenseTypeList();
            this.getRol();
            this.getBranchOfficeList();
            this.getPosts();
        },
        data: function() {
            return {
                form: {
                    payment_date: '',
                    payment_comment: '',
                    payment_type_id: null,
                    amount_to_pay: 0
                },
                rol_id: this.rol_id,
                postsSelected: "",
                dte_type_posts: [],
                region_posts: [],
                posts: [],
                selected: [],
                branch_office_posts: [],
                expense_type_posts: [],
                collection_post: null,
                z_inform_number: 'N/A',
                splits: [{
                    split_amount: '',
                    split_period: ''
                }]
            }
        },
        methods: {
            add() {
                this.splits.push({
                    split_amount: '',
                    split_period: ''
                })
            },
            getPosts() {
                if(this.form.supervisor_id == '') {
                    this.form.supervisor_id = null;
                }

                this.form.expense_type_id = null;
                this.form.since = null;
                this.form.until = null;
                this.form.status_id = null;

                axios.get('/api/capitulation/search/'+ this.$route.params.rut +'/'+ this.form.expense_type_id +'/'+ this.form.since +'/'+this.form.until+'/'+this.form.status_id+'?page='+this.currentPage+'&api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data.data;
                    this.total = response.data.data.last_page;
                    this.currentPage = response.data.data.current_page;
                    this.quantity = response.data.data.total;
                    this.rowsQuantity = response.data.data.total;
                });
            },
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

                axios.post('/api/capitulation/check?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.imputeCollection.reset(); // This will clear that form

                this.$awn.success("El registro ha sido imputado", {labels: {success: "Éxito"}});

                this.$router.push('/capitulation');
            },
            getExpenseTypeList() {
                axios.get('/api/expense_type?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
                });
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            formatPrice(value) {
                let val = (value/1).toFixed(0).replace('.', ',')
                return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
            },
            formatDate(value) {
                return moment(value).format('DD-MM-YYYY');
            }
        },
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
                            selected.push(post.capitulation_id);
                        });
                    }

                    this.selected = selected;
                }
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
