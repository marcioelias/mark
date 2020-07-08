<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-sms fa-2x"></i> Nova mensagem SMS
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="action_description" placeholder="Exemplo: Enviar SMS">
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
                    <textarea class="form-control" ref="sms_body" id="exampleFormControlTextarea1" rows="3" placeholder="Digite o texto para a mensagem SMS..." v-model="action_data.data"></textarea>
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
import * as componentTypes from '../../steps/components/component-types'

const iniData = {
    id: null,
    action_description: 'Enviar SMS',
    action_sequence: 0,
    action_data: {
        data: '',
        options: {}
    },
    action_type_id: null
}

export default {
    data() {
        return {
            ...iniData
        }
    },
    components: {
        SelectVariables
    },
    computed: {
        ...mapState('steps', [
            'isEditing', 'editingIndex'
        ]),
        ...mapGetters('steps', [
            'GetActionByIndex'
        ]),
        ...mapGetters('funnel', [
            'GetActionTypeByName'
        ])
    },
    methods: {
        ...mapActions('steps', [
            'ActionSetActiveComponent', 'ActionSetNewAction', 'ActionSetUpdateAction'
        ]),
        saveSmsAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
            if (this.isEditing) {
                this.ActionSetUpdateAction(this.$data)
            } else {
                this.ActionSetNewAction(this.$data)
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
                        this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
                    }
                })
        },
        clearForm() {
            this.smsAction = { ...iniData }
        }
    },
    mounted() {
        if (this.isEditing) {
            Object.assign(this.$data, { ...this.GetActionByIndex(this.editingIndex) })
        } else {
            this.action_type_id = this.GetActionTypeByName('sms').id
        }
    }
}
</script>
