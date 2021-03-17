<template>
    <div>
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <h1 class="h3 mb-2 text-gray-800">
                Editar Banco
            </h1>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form @submit.prevent="onSubmit" ref="editBank">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" 
                                v-model="form.name"
                                :class="{
                                    'is-valid':!$v.form.name.$error && $v.form.name.$dirty,
                                    'is-invalid':$v.form.name.$error && $v.form.name.$dirty
                                }"
                                placeholder="Ingresa el nombre del banco"
                                >
                            </div>
                            <button 
                            :disabled="!formValid"
                            type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="text">Actualizar</span>
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
        created() {
            this.getPost();
        },
        data: function() {
            return {
                form: {
                    name: ""
                }
            }
        },
        methods: {
            getPost() {
                axios.get('/api/bank/'+ this.$route.params.id +'/edit')
                .then(response => {
                    this.post = response.data.data;
                    this.$set(this.form, 'name', this.post.bank)
                });
            },
            onSubmit() {
                if(this.formValid) {
                    axios.put('/api/bank/'+this.$route.params.id, {
                        name: this.$v.form.name.$model
                    })
                    .then(function (response) {
                        console.log('enviado' + this.form.name);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                    this.$refs.editBank.reset(); // This will clear that form

                    this.$awn.success("El registro ha sido actualizado", {labels: {success: "Éxito"}});

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
