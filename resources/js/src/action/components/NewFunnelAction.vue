<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-filter fa-2x"></i>
            <template v-if="isEditing">
               {{ action_description }} [ Alterar ]
            </template>
            <template v-else>
                Mover Lead
            </template>
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col col-lg-4">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="actionDescription" placeholder="Exemplo: Mover Lead">
                </div>
                <div class="col col-lg-2">
                    <NumberEdit name="days_after" id="days_after" :min="0" :max="30" v-model="options.days_after">Executar após dias</NumberEdit>
                </div>
                <div class="col col-lg-6">
                    <label for="exampleFormControlTextarea1">Novo Funil</label>
                    <Select2 v-model="data" class="w-100" name="funnel_id" id="funnel_id" :options="GetRemarketingFunnelsForSelect" />
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button class="btn btn-secondary float-right" @click="cancelNewFunnelAction"><i class="fas fa-times"></i> Cancelar</button>
                    <button class="btn btn-success float-right mr-1" @click="saveFunnelAction"><i class="fas fa-save"></i> Salvar Ação</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import NumberEdit from '../../components/NumberEdit'
import flatPickr from 'vue-flatpickr-component'
import * as componentTypes from '../component-types'
import Select2 from 'v-select2-component'

import 'flatpickr/dist/flatpickr.css'

const iniData = () => {
    return {
        data: '',
        options: {
            days_after: 0,
            start_time: '00:00',
            end_time: '23:59',
            delay_minutes: 0
        }
    }
}

export default {
    data() {
        return {
            ...iniData(),
        }
    },
    components: {
        NumberEdit, flatPickr, Select2
    },
    computed: {
        ...mapState('action', [
            'id',
            'action_type_id',
            'action_sequence',
            'action_description',
            'action_data',
            'editingIndex'
        ]),
        ...mapState('step', [
            'actions', 'actionEditingIndex'
        ]),
        ...mapState('funnel', [
            'isSalesFunnel'
        ]),
        ...mapGetters('funnel', [
            'GetActionTypeByName'
        ]),
        ...mapGetters('action', [
            'GetRemarketingFunnelsForSelect'
        ]),
        actionDescription: {
            get() {
                return this.action_description
            },
            set(value) {
                this.ActionSetActionDescription(value)
            }
        },
        isEditing() {
            return this.actionEditingIndex !== null
        }
    },
    methods: {
        ...mapActions('action', [
            'ActionSetId',
            'ActionSetActionTypeId',
            'ActionSetActionSequence',
            'ActionSetActionDescription',
            'ActionSetActionData',
            'ActionClearState',
            'ActionSetRemarketingFunnels'
        ]),
        ...mapActions('step', [
            'ActionAddNewAction', 'ActionSetActionComponent', 'ActionUpdateAction', 'ActionSetEditActionIndex'
        ]),
        ...mapActions('funnel', [
            'ActionSetShowCrudAction', 'ActionAddNewStep', 'ActionUpdateStep'
        ]),
        saveFunnelAction() {
            this.ActionSetActionData({ ...this.$data })
            if (!this.isEditing) {
                this.ActionSetId(this.$uuid())
                this.ActionAddNewAction()
                    .then(() => {
                        this.clearForm()
                        if (!this.isSalesFunnel) {
                            this.$emit('new-action-added', { ...this.$data })
                        }
                    })
            } else {
                this.ActionUpdateAction()
                    .then(() => {
                        this.clearForm()
                        if (!this.isSalesFunnel) {
                            this.$emit('action-updated', { ...this.$data })
                        }
                    })
            }
        },
        cancelNewFunnelAction() {
            this.$swal.fire({
                    title: 'Cancelar cadastro da ação?',
                    text: `Os dados informados serão perdidos...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        if (this.isEditing) {
                            this.ActionSetEditActionIndex(null)
                        }
                        this.clearForm()
                    }
                })
        },
        clearForm() {
            Object.assign(this.$data, { ...iniData() })
            this.ActionClearState()
            this.ActionSetActionComponent(componentTypes.ACTIONS_TABLE)
        },
        doOnChangeDelayDays(value) {
            //console.log(`Dias: ${value}`)
        },
        doOnChangeDelayHours(value) {
            //console.log(`Horas: ${value}`)
        },
        doOnChangeDelayMinutes(value) {
            //console.log(`Minutos: ${value}`)
        }
    },
    mounted() {
        this.ActionSetShowCrudAction(true)
        this.ActionSetRemarketingFunnels({ vm: this })
        if (this.isEditing) {
            Object.assign(this.$data, this.action_data)
        } else {
            this.actionDescription = 'Novo Funil'
            this.ActionSetActionTypeId(this.GetActionTypeByName('funnel').id)
            this.ActionSetActionSequence((this.actions.length ?? 0) + 1)
        }
    },
    destroyed() {
        this.ActionSetShowCrudAction(false)
    },
}
</script>
