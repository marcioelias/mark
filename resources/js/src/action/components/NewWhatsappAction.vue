<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fab fa-whatsapp fa-2x"></i>
            <template v-if="isEditing">
               {{ action_description }} [ Alterar ]
            </template>
            <template v-else>
                Nova mensagem Whatsapp
            </template>
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Atenção</h4>
                <p class="mb-0">
                    <small>Ações de envio de mensagens de Whatsapp serão executadas somente caso o produto em questão tenha uma instância de Whatsapp configurada e conectada.</small>
                </p>
            </div>
            <div class="row mb-1">
                <div class="col col-lg-4">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="actionDescription" placeholder="Exemplo: Enviar Whatsapp">
                </div>
                <div class="col col-lg-2">
                    <NumberEdit name="days_after" id="days_after" :min="0" :max="30" v-model="options.days_after">Enviar após dias</NumberEdit>
                </div>
                <div class="col col-lg-2">
                    <NumberEdit name="delay_days" id="delay_days" :min="0" :max="30" v-model="options.delay_minutes">Atraso/Minutos</NumberEdit>
                </div>
                <div class="col col-lg-4">
                    <label>Somente no período entre:</label>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <flat-pickr
                            v-model="options.start_time"
                            class="form-control"
                            :config="{
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: 'H:i',
                                time_24hr: true
                            }"
                        ></flat-pickr>
                        <span class="pl-1 pr-1"> e </span>
                        <flat-pickr
                            v-model="options.end_time"
                            class="form-control"
                            :config="{
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: 'H:i',
                                minTime: options.start_time,
                                time_24hr: true
                            }"
                        ></flat-pickr>
                    </div>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col">
                    <select-variables :component="'whatsapp_message'" />
                    <small><i class="fas fa-exclamation text-info"></i> Selecione variáveis para incluir dados pessoais, como Nome do Cliente, Link do Boleto, etc.</small>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="exampleFormControlTextarea1">Texto da Mensagem</label>
                    <div id="emoji-input-container">
                        <div class="emoji-input-box">
                            <textarea ref="whatsapp_message" class="form-control" v-model="data" @click="showDialog = false"></textarea>
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
            <div class="row mt-1">
                <div class="col">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                        <label class="custom-control-label" for="customCheck1">Enviar Boleto em PDF</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button class="btn btn-secondary float-right" @click="cancelNewWhatsappAction"><i class="fas fa-times"></i> Cancelar</button>
                    <button class="btn btn-success float-right mr-1" @click="saveWhatsappAction"><i class="fas fa-save"></i> Salvar mensagem</button>
                </div>
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
import NumberEdit from '../../components/NumberEdit'
import flatPickr from 'vue-flatpickr-component'
import * as componentTypes from '../component-types'
import insertTextAtCursor from 'insert-text-at-cursor'

import 'flatpickr/dist/flatpickr.css'



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
        SelectVariables, NumberEdit, flatPickr
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
            'ActionClearState'
        ]),
        ...mapActions('step', [
            'ActionAddNewAction', 'ActionSetActionComponent', 'ActionUpdateAction', 'ActionSetEditActionIndex'
        ]),
        ...mapActions('funnel', [
            'ActionSetShowCrudAction', 'ActionAddNewStep', 'ActionUpdateStep'
        ]),
        saveWhatsappAction() {
            const {showDialog, ...payload } = this.$data
            this.ActionSetActionData({ ...payload })
            if (!this.isEditing) {
                this.ActionSetId(this.$uuid)
                this.ActionAddNewAction()
                    .then(() => {
                        this.clearForm()
                        if (!this.isSalesFunnel) {
                            this.$emit('new-action-added', { ...payload })
                        }
                    })
            } else {
                this.ActionUpdateAction()
                    .then(() => {
                        this.clearForm()
                        if (!this.isSalesFunnel) {
                            this.$emit('action-updated', { ...payload })
                        }
                    })
            }
        },
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
        },
        onInput(event) {
            //console.log(event.data)
          //event.data contains the value of the textarea
        },
        clearTextarea(){
            this.$refs.whatsapp_message.clear()
        },
        getInputObj() {
            //console.log(this.$parent.$refs['whatsapp_message'])
            return this.$parent.$refs['whatsapp_message'];
        },
        toogleDialogEmoji() {
            this.showDialog = !this.showDialog;
        },
        onSelectEmoji(emoji) {
            insertTextAtCursor(this.$refs['whatsapp_message'], emoji.data)
            //this.valueInput += emoji.data;
            // Optional
            // this.toogleDialogEmoji();
        }
    },
    mounted() {
        this.ActionSetShowCrudAction(true)
        if (this.isEditing) {
            Object.assign(this.$data, this.action_data)
        } else {
            this.actionDescription = 'Enviar Whatsapp'
            this.ActionSetActionTypeId(this.GetActionTypeByName('whatsapp').id)
            this.ActionSetActionSequence((this.actions.length ?? 0) + 1)
        }
    },
    destroyed() {
        this.ActionSetShowCrudAction(false)
    },
}
</script>
