<template>
    <table class="table table-sm table-hover bg-white mb-0">
        <thead class="bg-primary text-white">
            <tr>
                <td>Descrição</td>
                <td>Enviar após</td>
                <td>Somente entre</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in OrderedActions" :key="index">
                <td class="align-middle" scope="row">
                    <i :class="getActionIcon(item)"></i> {{ item.action_description }}
                </td>
                <td class="align-middle">
                    {{ item.action_data.options.days_after }} dias
                </td>
                <td class="align-middle">
                    {{ item.action_data.options.start_time }} / {{ item.action_data.options.end_time }} horas
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
import * as componentTypes from '../component-types'

export default {
    computed: {
        ...mapGetters('step', [
            'OrderedActions',
        ]),
        ...mapState('funnel', [
            'actionTypes', 'isSalesFunnel'
        ]),
        ...mapGetters('funnel', [
            'GetActionTypeById'
        ]),
        ...mapState('action', {
            actionTypeId: 'action_type_id'
        })
    },
    methods: {
        ...mapActions('step', [
            'ActionDelAction', 'ActionSetActionComponent', 'ActionSetEditActionIndex'
        ]),
        ...mapActions('action', [
            'ActionEditAction'
        ]),
        removeAction(index) {
            this.$swal.fire({
                    title: 'Remover a ação?',
                    text: `Será removida a ação ${this.OrderedActions[index].action_description}.`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, remover!',
                    cancelButtonText: 'Não, cancelar.'
                }).then(result => {
                    if (result.value) {
                        this.ActionDelAction(index)
                        if (!this.isSalesFunnel) {
                            this.$emit('deleted-action', index)
                        }
                    }
                })
        },
        editAction(index) {
            this.ActionEditAction(this.OrderedActions[index])
                .then(() => {
                    this.ActionSetEditActionIndex(index)
                    this.ActionSetActionComponent(this.getComponentByAction())
                })
        },
        getComponentByAction() {
            let actionType = this.actionTypes.find(act => act.id === this.actionTypeId)
            switch (actionType.action_type_name) {
                case 'email':
                    return componentTypes.NEW_EMAIL_ACTION
                    break;

                case 'sms':
                    return componentTypes.NEW_SMS_ACTION
                    break;

                case 'whatsapp':
                    return componentTypes.NEW_WHATSAPP_ACTION
                    break;
            }
        },
        getActionIcon(item) {
            let act = this.actionTypes.find(a => a.id === item.action_type_id)
            return {
               'fas fa-envelope': act.action_type_name == 'email',
               'fas fa-sms': act.action_type_name == 'sms',
               'fab fa-whatsapp': act.action_type_name == 'whatsapp'
            }
        }
    }
}
</script>
