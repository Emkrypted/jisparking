<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="createDteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
        </div>
    </div>
    
</template>

<script>
    export default {
        created() {
            this.getDteTypeList();
            this.getRegionList();
            this.getBranchOfficeList();
            this.getExpenseTypeList();
            setTimeout(function() {
                $('#createDteModal').modal('show');
            }, 1000);
        },
        data: function() {
            return {
                form: {
                    dte_type_id: null,
                    branch_office_id: null,
                    rut: '',
                    address: '',
                    client: '',
                    email: '',
                    amount: '',
                    expense_type_id: null,
                },
                postsSelected: "",
                dte_type_posts: [],
                expense_type_posts: [],
                region_posts: [],
                branch_office_posts: [],
                collection_post: null,
                z_inform_number: 'N/A'
            }
        },
        methods: {
            onSubmit(e) {
                e.preventDefault();
                let currentObj = this;
    
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }

                let formData = new FormData();
                formData.append('dte_type_id', this.form.dte_type_id);
                formData.append('rut', this.form.rut);
                formData.append('client', this.form.client);
                formData.append('email', this.form.email);
                formData.append('address', this.form.address);
                formData.append('amount', this.form.amount);
                formData.append('expense_type_id', this.form.expense_type_id);
                formData.append('branch_office_id', this.form.branch_office_id);

                axios.post('/api/dte/generate?api_token='+App.apiToken, formData, config)
                .then(function (response) {
                    currentObj.success = response.data.success;
                })
                .catch(function (error) {
                    console.log(error);
                });

                this.$refs.createCollection.reset(); // This will clear that form

                this.$awn.success("El registro ha sido agregado", {labels: {success: "Éxito"}});

                this.$router.push('/dte');
            },
            getSupplier() {
                axios.get('/api/supplier/'+this.form.rut+'?api_token='+App.apiToken)
                .then(response => {
                    this.posts = response.data.data;
                });
            },
            getDteTypeList() {
                axios.get('/api/dte_type?api_token='+App.apiToken)
                .then(response => {
                    this.dte_type_posts = response.data.data;
                });
            },
            getRegionList() {
                axios.get('/api/region?api_token='+App.apiToken)
                .then(response => {
                    console.log(response.data.data);
                    this.region_posts = response.data.data;
                });
            },
            getBranchOfficeList() {
                axios.get('/api/branch_office?api_token='+App.apiToken)
                .then(response => {
                    this.branch_office_posts = response.data.data;
                });
            },
            getExpenseTypeList() {
                axios.get('/api/expense_type?api_token='+App.apiToken)
                .then(response => {
                    this.expense_type_posts = response.data.data;
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
