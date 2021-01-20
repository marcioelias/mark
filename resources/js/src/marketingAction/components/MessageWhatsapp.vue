<template>
    <div>
        <div class="row mb-1 px-1 d-flex justify-content-between align-items-start">
            <div>
                <select-variables :component="'whatsapp_message'" />
                <small><i class="fas fa-exclamation text-info"></i> Selecione variáveis para incluir dados pessoais, como Nome do Cliente, Link do Boleto, etc.</small>
            </div>
            <button class="btn btn-danger" @click="cancelNewWhatsappAction"><i class="fa fa-times"></i> Cancelar</button>
        </div>
        <div class="row">
            <div class="col">
                <label for="exampleFormControlTextarea1">Texto da Mensagem</label>
                <div id="emoji-input-container">
                    <div class="emoji-input-box">
                        <textarea ref="whatsapp_message" class="form-control" v-model="currentMessage" @click="showDialog = false"></textarea>
                        <button class="btn" id="toggleEmoji" @click="toogleDialogEmoji"><i class="fa fa-smile text-light"></i></button>
                    </div>
                </div>
                <VEmojiPicker
                    v-show="showDialog"
                    :limitFrequently="5"
                    :emojisByRow="7"
                    :continuousList="false"
                    :showSearch="false"
                    :i18n="{
                        search: 'Pesquisar...',
                        categories: {
                            Activity: 'Atividades',
                            Flags: 'Bandeiras',
                            Foods: 'Comida',
                            Frequently: 'Frequentes',
                            Objects: 'Objetos',
                            Nature: 'Natureza',
                            Peoples: 'Pessoas',
                            Symbols: 'Símbolos',
                            Places: 'Locais'
                        }
                    }"
                    @select="onSelectEmoji"
                />
            </div>
        </div>
    </div>
</template>

<style lang="css">
#emoji-input-container {
  position: relative;
}

.emoji-input-box {
  display: flex;
  align-items: center;
  justify-content: center;
}
textarea {
  padding-left: 30px !important;
}

button#toggleEmoji {
    position: absolute;
    left: 0px;
    top: 0px;
    font-size: 1.5rem;
    padding: 0.5rem;
}
</style>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import SelectVariables from '../../variables/components/SelectVariables'
import insertTextAtCursor from 'insert-text-at-cursor'

const iniData = () => {
    return {
        showDialog: false,
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
        },
    },
    methods: {
        ...mapActions('marketingAction', [
            'ActionSetMessageType',
            'ActionSetMessage',
            'ActionCancelMessage'
        ]),
        cancelNewWhatsappAction() {
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
        },
        clearForm() {
            Object.assign(this.$data, { ...iniData() })
            this.ActionClearState()
            this.ActionSetActionComponent(componentTypes.ACTIONS_TABLE)
        },
        clearTextarea(){
            this.$refs.whatsapp_message.clear()
        },
        getInputObj() {
            return this.$parent.$refs['whatsapp_message'];
        },
        toogleDialogEmoji() {
            this.showDialog = !this.showDialog;
        },
        onSelectEmoji(emoji) {
            insertTextAtCursor(this.$refs['whatsapp_message'], emoji.data)
        }
    },
    mounted() {
        if (this.isEditing) {
            Object.assign(this.$data, this.action_data)
        } else {
            this.actionDescription = 'Enviar Whatsapp'
        }
    },
}
</script>
