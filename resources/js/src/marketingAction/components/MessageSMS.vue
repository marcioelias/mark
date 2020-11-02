<template>
    <div>
        <div class="row mb-1 px-1 d-flex justify-content-between align-items-start">
            <div>
                <select-variables :component="'sms_body'" />
                <small><i class="fas fa-exclamation text-info"></i> Selecione variáveis para incluir dados pessoais, como Nome do Cliente, Link do Boleto, etc.</small>
            </div>
            <button class="btn btn-danger" @click="cancelar"><i class="fa fa-times"></i> Cancelar</button>
        </div>
        <div class="row">
            <div class="col">
                <label for="exampleFormControlTextarea1">Texto da Mensagem</label>
                <textarea class="form-control" :class="{ 'is-invalid': httpErrors.hasOwnProperty('message') }" ref="sms_body" id="exampleFormControlTextarea1" rows="3" placeholder="Digite o texto para a mensagem SMS..." v-model="currentMessage"></textarea>
                <span v-show="httpErrors.hasOwnProperty('message')" class="invalid-feedback" style="display: block">
                    <span v-for="(error, index) in httpErrors.message" :key="index">{{ error }}</span>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import SelectVariables from '../../variables/components/SelectVariables'
export default {
    components: {
        SelectVariables
    },
    computed: {
        ...mapState('marketingAction', [
            'httpErrors',
            'message'
        ]),
        currentMessage: {
            get() {
                return this.message.data
            },
            set(value) {
                this.ActionSetMessage(value)
            }
        }
    },
    methods: {
        ...mapActions('marketingAction', [
            'ActionSetMessageType',
            'ActionSetMessage',
            'ActionCancelMessage'
        ]),
        cancelar() {
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
                    this.ActionCancelMessage()
                }
            })
        }
    }
}
</script>
