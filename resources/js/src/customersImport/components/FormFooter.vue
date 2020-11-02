<template>
    <nav class="navbar navbar-expand-lg fixed-bottom p-0 mt-1">
        <div class="ml-auto">
            <button type="button" @click="saveImportedData" class="btn btn-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Salvar">
                <i class="fa fa-check"></i>
            </button>
            <button type="button" @click="cancelar" class="btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </nav>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    methods: {
        ...mapActions('customersImport', [
            'ActionSaveImportedData',
            'ActionSetHttpErrors'
        ]),
        saveImportedData() {
            this.ActionSaveImportedData({ vm: this })
                    .then(res => {
                        console.log(res)
                        if (res.status === 200) {
                            this.$swal.fire({
                                title: res.data.title,
                                text: res.data.message,
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
        },
        cancelar() {
            this.$swal.fire({
                    title: 'Cancelar Importação?',
                    text: `Nenhum dado será importado...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        window.location = '/customer'
                    }
                })
        }
    }
}
</script>
