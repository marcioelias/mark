<template>
    <nav class="navbar navbar-expand-lg fixed-bottom p-0 mt-1">
        <div class="ml-auto">
            <button type="button" @click="saveMarkeringAction" class="btn btn-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Salvar">
                <i class="fa fa-check"></i>
            </button>
            <button type="button" class="btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </nav>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    methods: {
        ...mapActions('marketingAction', [
            'ActionSaveMarketingAction',
            'ActionSetHttpErrors'
        ]),
        saveMarkeringAction() {
            this.ActionSaveMarketingAction({ vm: this })
                    .then(res => {
                        //console.log(res)
                        if (res.status === 200) {
                            this.$swal.fire({
                                title: 'Sucesso!',
                                text: 'Registro incluído.',
                                icon: 'success',
                                confirmButtonText: 'Ok',
                                padding: '2em'
                            }).then(() => window.location =  res.data.redirect)
                        }
                    })
                    .catch(err => {
                        switch (err.response.status) {
                            case 422:
                                this.$swal.fire({
                                    title: 'Ooops!',
                                    text: 'Algo deu errado.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok',
                                    padding: '2em'
                                }).then(() => this.ActionSetHttpErrors(err.response.data.errors))
                                break

                            default:
                                this.$swal.fire({
                                    title: 'Ooops!',
                                    text: 'Algo deu errado.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok',
                                    padding: '2em'
                                })
                                break
                        }
                    })
        }
    }
}
</script>
