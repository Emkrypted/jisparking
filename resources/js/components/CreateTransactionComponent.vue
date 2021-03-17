<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Crear Recaudación
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="createBank">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="$v.form.name.$model"
                                :class="{
                                    'is-valid':!$v.form.name.$error && $v.form.name.$dirty,
                                    'is-invalid':$v.form.name.$error && $v.form.name.$dirty
                                }"
                                placeholder="Ingresa el nombre del banco">
                            </div>
                            <button 
                            :disabled="!formValid"
                            type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Guardar</span>
                            </button>

                            <router-link to="/bank" class="btn btn-danger btn-icon-split">
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
    import { required, minLength } from 'vuelidate/lib/validators'

    export default {
        created() {},
        data: function() {
            return {
                form: {
                    name: ""
                }
            }
        },
        methods: {
            onSubmit() {
                if(this.formValid) {
                    axios.post('/api/bank/store', {
                        name: this.$v.form.name.$model
                    })
                    .then(function (response) {
                        console.log('enviado' + this.form.name);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                    this.$refs.createBank.reset(); // This will clear that form

                    this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                    this.$router.push('/bank');
                }
            }
        },
        computed: {
            formValid() {
                return !this.$v.$invalid;
            }
        },
        validations: {
            form: {
                name: {
                    required,
                    minLength: minLength(2)
                }
            }
        }
    }
</script>
<style lang="scss">
    @import '~vue-awesome-notifications/dist/styles/style.scss';
</style>
