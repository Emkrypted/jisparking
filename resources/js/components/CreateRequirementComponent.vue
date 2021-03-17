<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Solicitar Requerimiento
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createRequirement" enctype="multipart/form-data" novalidate>
                            
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Requerimiento</label>
                                <select class="form-control" id="exampleFormControlSelect1"
                                v-on:change="getRequirementInputs"
                                v-model="form.requirement_type_id"
                                >
                                    <option :value="null">-Seleccionar-</option>
                                    <option v-if="rol_id == 1 || rol_id == 4" :key="2" :value="2">Honorario</option>
                                    <option v-if="rol_id == 1 || rol_id == 4" :key="3" :value="3">Mantención</option>
                                    <option v-if="rol_id == 1 || rol_id == 4" :key="4" :value="4">Publicidad</option>
                                </select>
                            </div>
                            <div v-show="requirement_status_1">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Empleado</label>
                                    <div class="form-group">
                                        <select class="form-control" id="exampleFormControlSelect1"
                                        v-model="form.user_id"
                                                >
                                            <option :value="null">-Seleccionar-</option>
                                            <option v-for="employee_post in employee_posts" :key="employee_post.rut" :value="employee_post.rut">{{ employee_post.names }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Monto Bruto($)</label>
                                    <input
                                        type="number"
                                        v-model="form.gross_amount" 
                                        class="form-control"
                                        placeholder="Ingresa el monto bruto"
                                    >
                                </div>
                            </div>
                            <div v-show="requirement_status_2">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Motivo</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.reason"
                                    >
                                        <option value="null">-Seleccionar-</option>
                                        <option value="Licencia Médica">Licencia Médica</option>
                                        <option value="Vacaciones">Vacaciones</option>
                                        <option value="Reemplazo">Reemplazo</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sucursal</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.branch_office_id"
                                    v-on:change="getFilteredEmployeeList"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Colaborardor a Sustituir</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.employee_to_replace"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="filtered_employee_post in filtered_employee_posts" :key="filtered_employee_post.rut" :value="filtered_employee_post.rut">{{ filtered_employee_post.names }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Inicio de Reemplazo</label>
                                    <input
                                    type="date" 
                                    v-model="form.start_date" 
                                    class="form-control"
                                    placeholder="Ingresa la fecha de inicio de reemplazo"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fin de Reemplazo</label>
                                    <input
                                    type="date" 
                                    v-model="form.end_date" 
                                    class="form-control"
                                    placeholder="Ingresa la fecha de culminación de reemplazo"
                                    >
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">RUT</label>
                                    <input
                                    type="text" 
                                    v-model="form.rut" 
                                    class="form-control" 
                                    v-mask="'########-#'"
                                    placeholder="Ingresa el rut"
                                    >
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Nombre Completo</label>
                                    <input
                                    type="text" 
                                    v-model="form.full_name" 
                                    class="form-control"
                                    placeholder="Ingresa el nombre completo"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Correo</label>
                                    <input
                                    type="text" 
                                    v-model="form.email" 
                                    class="form-control"
                                    placeholder="Ingresa el correo"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Dirección</label>
                                    <input
                                    type="text" 
                                    v-model="form.address" 
                                    class="form-control"
                                    placeholder="Ingresa la dirección"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">¿Es extranjero?</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.foreigner_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option :key="1" :value="1">Si</option>
                                        <option :key="2" :value="2">No</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Bancos</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.bank_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="bank_post in bank_posts" :key="bank_post.bank_id" :value="bank_post.bank_id">{{ bank_post.bank }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">N° de Cuenta</label>
                                    <input
                                        type="number"
                                        v-model="form.account_number" 
                                        class="form-control"
                                        placeholder="Ingresa el número de cuenta"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Tipo de Horario</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.employee_schedule_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option :key="1" :value="1">Full-Time</option>
                                        <option :key="2" :value="2">Part-Time</option>
                                    </select>
                                </div>
                            </div>
                            <div v-show="requirement_status_3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Motivo</label>
                                    <input
                                    type="text" 
                                    v-model="form.reason" 
                                    class="form-control"
                                    placeholder="Ingresa el motivo"
                                    >
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Sucursal</label>
                                    <select class="form-control" id="exampleFormControlSelect1"
                                    v-model="form.branch_office_id"
                                    >
                                        <option :value="null">-Seleccionar-</option>
                                        <option v-for="branch_office_post in branch_office_posts" :key="branch_office_post.branch_office_id" :value="branch_office_post.branch_office_id">{{ branch_office_post.branch_office }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Evidencia</label>
                                    <input ref="file" accept="image/x-png,image/gif,image/jpeg" type="file" class="form-control" v-on:change="onFileChange" required>
                                </div>
                            </div>
                            <div v-show="requirement_status_4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Motivo</label>
                                    <input
                                    type="text" 
                                    v-model="form.reason" 
                                    class="form-control"
                                    placeholder="Ingresa el motivo"
                                    >
                                </div>
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
                            <button 
                            type="submit"
                            class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>
                            <router-link to="/requirement" class="btn btn-danger btn-icon-split">
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
    export default {
        created() {
            this.getEmployeeList();
            this.getBranchOfficeList();
            this.getBankList();
            this.getRol();
        },
        data: function() {
            return {
                form: {
                    branch_office_id: null,
                    requirement_type_id: null,
                    gross_amount: null,
                    user_id: null,
                    reason: null,
                    rut: '',
                    email: '',
                    address: '',
                    foreigner_id: null,
                    gross_amount: '',
                    bank_id: null,
                    account_number: '',
                    employee_schedule_id: null,
                    full_name: null,
                    employee_to_replace: null,
                    start_date: '',
                    end_date: ''
                },
                rol_id: this.rol_id,
                requirement_status_1: false,
                requirement_status_2: false,
                requirement_status_3: false,
                requirement_status_4: false,
                postsSelected: "",
                bank_posts: [],
                employee_posts: [],
                filtered_employee_posts: [],
                branch_office_posts: [],
                collection_post: null,
                z_inform_number: 'N/A',
                noFile: false
            }
        },
        methods: {
            onFileChange(e){
                this.file = e.target.files[0];
                this.noFile = e.target.files.length;
            },
            getRol() {
                axios.get('/api/user?api_token='+App.apiToken)
                .then(response => {
                    console.log(response);
                    this.rol_id = response.data.data.rol_id;
                });
            },
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('requirement_type_id', this.form.requirement_type_id);
                formData.append('branch_office_id', this.form.branch_office_id);
                formData.append('foreigner_id', this.form.foreigner_id);
                formData.append('bank_id', this.form.bank_id);
                formData.append('employee_schedule_id', this.form.employee_schedule_id);
                formData.append('rut', this.form.rut);
                formData.append('reason', this.form.reason);
                formData.append('full_name', this.form.full_name);
                formData.append('email', this.form.email);
                formData.append('user_id', this.form.user_id);
                formData.append('gross_amount', this.form.gross_amount);
                formData.append('start_date', this.form.start_date);
                formData.append('end_date', this.form.end_date);
                formData.append('account_number', this.form.account_number);
                formData.append('employee_to_replace', this.form.employee_to_replace);
                formData.append('address', this.form.address);
                formData.append('file', this.file);

                axios.post('/api/requirement/store?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createRequirement.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/requirement');
            },
            getRequirementInputs() {
                if(this.form.requirement_type_id == 1) {
                    this.requirement_status_1 = true;
                    this.requirement_status_2 = false;
                    this.requirement_status_3 = false;
                    this.requirement_status_4 = false;
                } else if(this.form.requirement_type_id == 2) {
                    this.requirement_status_1 = false;
                    this.requirement_status_2 = true;
                    this.requirement_status_3 = false;
                    this.requirement_status_4 = false;
                } else if(this.form.requirement_type_id == 3) {
                    this.requirement_status_1 = false;
                    this.requirement_status_2 = false;
                    this.requirement_status_3 = true;
                    this.requirement_status_4 = false;
                } else if(this.form.requirement_type_id == 4) {
                    this.requirement_status_1 = false;
                    this.requirement_status_2 = false;
                    this.requirement_status_3 = false;
                    this.requirement_status_4 = true;
                }
            },
            getEmployeeList() {
                axios.get('/api/employee?api_token='+App.apiToken)
                .then(response => {
                    this.employee_posts = response.data.data;
                });
            },
            getFilteredEmployeeList() {
                axios.post('/api/employee/list/'+ this.form.branch_office_id +'?api_token='+App.apiToken)
                .then(response => {
                    this.filtered_employee_posts = response.data.data;
                });
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            getBankList() {
                axios.get('/api/bank?api_token='+App.apiToken)
                .then(response => {
                    this.bank_posts = response.data.data;
                });
            }
        },
        computed: {
            isDisabled() {
                return true;
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
