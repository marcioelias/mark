<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-sms fa-2x"></i>
            <template v-if="isEditing">
               {{ action_description }} [ Alterar ]
            </template>
            <template v-else>
                Nova mensagem SMS
            </template>
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col col-lg-4">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="actionDescription" placeholder="Exemplo: Enviar SMS">
                </div>

                <template v-if="options.extra.envio_imediato">
                    <div class="col col-lg-4 d-flex">
                        <div class="btn-group" role="group" aria-label="Agendamento Disparo">
                            <button type="button" class="btn" @click="options.extra.envio_imediato = true"
                            :class="{'btn-primary': options.extra.envio_imediato == null, 'btn-warning': options.extra.envio_imediato}">Envio imediato</button>
                            <button type="button" @click="options.extra.envio_imediato = false" class="btn btn-primary">Envio agendado</button>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="col col-lg-2">
                        <NumberEdit name="days_after" id="days_after" :min="0" :max="30" v-model="options.days_after">Enviar após dias</NumberEdit>
                    </div>
                    <div class="col col-lg-2">
                        <NumberEdit name="delay_minutes" id="delay_minutes" :min="0" :max="15" v-model="options.delay_minutes">Atraso/Minutos</NumberEdit>
                    </div>
                </template>

                <template v-if="options.extra.qualquer_horario">
                    <div class="col col-lg-4 d-flex">
                        <div class="btn-group" role="group" aria-label="Horário de disparo">
                            <button type="button" class="btn" @click="options.extra.qualquer_horario = true"
                            :class="{'btn-primary': options.extra.qualquer_horario == null, 'btn-warning': options.extra.qualquer_horario}">Qualquer horário</button>
                            <button type="button" class="btn btn-primary" @click="options.extra.qualquer_horario = false">Horário limitado</button>
                        </div>
                    </div>
                </template>
                <template v-else>
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
                        <!-- <NumberEdit name="delay_hours" id="delay_hours" :min="0" :max="23" @on-change="doOnChangeDelayHours">horas</NumberEdit> -->
                    </div>
                </template>
                <!-- <div class="col col-md-2">
                    <NumberEdit name="delay_minutes" id="delay_minutes" :min="0" :max="59" @on-change="doOnChangeDelayMinutes">minutos</NumberEdit>
                </div> -->
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
import NumberEdit from '../../components/NumberEdit'
import flatPickr from 'vue-flatpickr-component'
import * as componentTypes from '../component-types'

import 'flatpickr/dist/flatpickr.css'

const iniData = () => {
    return {
        data: '',
        options: {
            days_after: 0,
            start_time: '00:00',
            end_time: '23:59',
            delay_minutes: 0,
            extra: {
                envio_imediato: true,
                qualquer_horario: true
            }
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
        saveSmsAction() {
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
        },
        doOnChangeDelayDays(value) {
            //
        },
        doOnChangeDelayHours(value) {
            //
        },
        doOnChangeDelayMinutes(value) {
            //
        }
    },
    mounted() {
        this.ActionSetShowCrudAction(true)
        if (this.isEditing) {
            Object.assign(this.$data, this.action_data)
        } else {
            this.actionDescription = 'Enviar SMS'
            this.ActionSetActionTypeId(this.GetActionTypeByName('sms').id)
            this.ActionSetActionSequence((this.actions.length ?? 0) + 1)
        }
    },
    destroyed() {
        this.ActionSetShowCrudAction(false)
    },
}
</script>
