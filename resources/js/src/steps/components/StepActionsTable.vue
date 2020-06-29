<template>
    <table class="table table-sm table-hover bg-white">
        <tbody>
            <tr v-for="(item, index) in listActions" :key="index">
                <td class="align-middle" scope="row">
                    <i class="fas" :class="{'fa-envelope': item.type == 'email', 'fa-sms': item.type == 'sms'}"></i> {{ item.description }}
                </td>
                <td class="align-middle text-right" scope="row">
                    <span data-toggle="tooltip" data-placement="top" title="Editar">
                        <a class="btn btn-sm btn-primary icon-btn-sm-padding waves-effect waves-light text-white" @click.prevent="editAction(index)"><i class="fa fa-edit" style="font-size: 1.2rem"></i></a>
                    </span>
                    <span data-toggle="tooltip" data-placement="top" title="Remover">
                        <a class="btn btn-sm btn-danger icon-btn-sm-padding waves-effect waves-light text-white" @click.prevent="removeAction(index)"><i class="fa fa-trash" style="font-size: 1.2rem"></i></a>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    computed: {
        ...mapState('steps', [
            'listActions'
        ])
    },
    methods: {
        ...mapActions('steps', [
            'ActionDelAction', 'ActionEditAction'
        ]),
        removeAction(index) {
            this.$swal.fire({
                    title: 'Remover a ação?',
                    text: `Será removida a ação ${this.listActions[index].description}.`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, remover!',
                    cancelButtonText: 'Não, cancelar.'
                }).then(result => {
                    if (result.value) {
                        this.ActionDelAction(index)
                    }
                })
        },
        editAction(index) {
            this.ActionEditAction(index)
        }
    }
}
</script>
