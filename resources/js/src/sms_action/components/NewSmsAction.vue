<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-sms fa-2x"></i> Nova mensagem SMS
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="smsAction.description" placeholder="Exemplo: Enviar SMS">
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
                    <textarea class="form-control" ref="sms_body" id="exampleFormControlTextarea1" rows="3" placeholder="Digite o texto para a mensagem SMS..." v-model="smsAction.textMessage"></textarea>
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
    type: 'sms',
    description: 'Enviar SMS',
    textMessage: '',
}

export default {
    data() {
        return {
            smsAction: { ...iniData }
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
        ])
    },
    methods: {
        ...mapActions('steps', [
            'ActionSetActiveComponent', 'ActionSetNewAction', 'ActionSetUpdateAction'
        ]),
        saveSmsAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
            if (this.isEditing) {
                this.ActionSetUpdateAction(this.smsAction)
            } else {
                this.ActionSetNewAction(this.smsAction)
            }
        },
        cancelNewSmsAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
        },
        clearForm() {
            this.smsAction = { ...iniData }
        }
    },
    mounted() {
        if (this.isEditing) {
            this.smsAction = { ...this.GetActionByIndex(this.editingIndex) }
        }
    }
}
</script>
