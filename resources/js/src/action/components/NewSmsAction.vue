<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-sms fa-2x"></i> Nova mensagem SMS
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="actionDescription" placeholder="Exemplo: Enviar SMS">
                </div>
            </div>
            <div class="row mb-1">
                <div class="col">
                    <select-variables :component="'sms_body'" />
                    <small><i class="fas fa-exclamation text-info"></i> Selecione variáveis para incluir dados pessoais, como Nome do Cliente, Link do Boleto, etc.</small>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="exampleFormControlTextarea1">Texto da Mensagem</label>
                    <textarea class="form-control" ref="sms_body" id="exampleFormControlTextarea1" rows="3" placeholder="Digite o texto para a mensagem SMS..." v-model="data"></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button class="btn btn-secondary float-right" @click="cancelNewSmsAction"><i class="fas fa-times"></i> Cancelar</button>
                    <button class="btn btn-success float-right mr-1" @click="saveSmsAction"><i class="fas fa-save"></i> Salvar mensagem</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import SelectVariables from '../../variables/components/SelectVariables'
import * as componentTypes from '../component-types'

const iniData = () => {
    return {
        data: '',
        options: {}
    }
}

export default {
    data() {
        return {
            ...iniData()
        }
    },
    components: {
        SelectVariables
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
        ...mapGetters('funnel', [
            'GetActionTypeByName'
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
            'ActionSetActionTypeId',
            'ActionSetActionSequence',
            'ActionSetActionDescription',
            'ActionSetActionData',
            'ActionClearState'
        ]),
        ...mapActions('step', [
            'ActionAddNewAction', 'ActionSetActionComponent', 'ActionUpdateAction', 'ActionSetEditActionIndex'
        ]),
        saveSmsAction() {
            this.ActionSetActionData({ ...this.$data })
            if (!this.isEditing) {
                this.ActionAddNewAction()
                    .then(() => this.clearForm())
            } else {
                this.ActionUpdateAction()
                    .then(() => this.clearForm())
            }
        },
        cancelNewSmsAction() {
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
        }
    },
    mounted() {
        if (this.isEditing) {
            Object.assign(this.$data, JSON.parse(this.action_data))
        } else {
            this.actionDescription = 'Enviar SMS'
            this.ActionSetActionTypeId(this.GetActionTypeByName('sms').id)
            this.ActionSetActionSequence((this.actions.length ?? 0) + 1)
        }
    }
}
</script>
