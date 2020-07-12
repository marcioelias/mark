<template>
    <div>
        <div class="card mb-1">
            <!-- <div class="card-header bg-primary text-white p-1">
                Configurações
            </div> -->
            <div class="card-body pl-0 pr-0 pt-0">
                <div class="row">
                    <div class="col-md-3">
                        <label for="delay_days">Executar Após Dias</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" @click="step.delay_days > 0 ? step.delay_days-- : 0"><i class="fas fa-minus"></i></button>
                            </div>
                            <input type="number" name="dalay_days" id="dalay_days" class="form-control" v-model="step.delay_days">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="step.delay_days < 30 ? step.delay_days++ : 30"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="delay_hours">Horas</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" @click="step.delay_hours > 0 ? step.delay_hours-- : 0"><i class="fas fa-minus"></i></button>
                            </div>
                            <input type="number" name="dalay_hours" id="dalay_hours" class="form-control" max="23" v-model="step.delay_hours">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="step.delay_hours < 23 ? step.delay_hours++ : 23"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="new_tag">Nova Tag</label>
                        <Select2 v-model="step.new_tag_id" name="new_tag" id="new_tag" :options="GetNewTagsForSelect" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-primary text-white p-1 d-flex justify-content-between align-items-center" v-if="actionComponent === actionComponentTypes.ACTIONS_TABLE">
                <div>Ações</div>
                <div class="dropdown">
                    <button class="btn btn-success  dropdown-toggle icon-btn-sm-padding" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus"></i> Nova ação
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" @click.prevent="newAction(actionComponentTypes.NEW_EMAIL_ACTION)"><i class="fas fa-envelope"></i> Enviar E-mail</a>
                        <a class="dropdown-item" @click.prevent="newAction(actionComponentTypes.NEW_SMS_ACTION)"><i class="fas fa-sms"></i> Enviar SMS</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <component :is="actionComponent"></component>
            </div>
            <div class="card-footer" v-if="actionComponent === actionComponentTypes.ACTIONS_TABLE">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-secondary float-right" @click="cancelSaveStep"><i class="fas fa-times"></i> Cancelar</button>
                        <button class="btn btn-success float-right mr-1" @click="saveStep"><i class="fas fa-save"></i> Salvar passo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
import { mapState, mapGetters, mapActions } from 'vuex'
import ActionsTable from '../../action/components/ActionsTable'
import NewSmsAction from '../../action/components/NewSmsAction'
import NewEmailAction from '../../action/components/NewEmailAction'
import * as componentTypes from '../../action/component-types'

const iniData = {
        id: null,
        funnel_step_sequence: 0,
        funnel_step_description: '',
        new_tag_id: null,
        delay_days: 0,
        delay_hours: 0,
    }

export default {
    data() {
        return {
            step: { ...iniData },
            actionComponentTypes: { ...componentTypes }
        }
    },
    components: {
        Select2, ActionsTable, NewSmsAction, NewEmailAction
    },
    computed: {
        ...mapState('step', [
            'actionComponent', 'actions'
        ]),
        ...mapState('funnel', [
            'steps', 'currentStep', 'isEditingStep'
        ]),
        ...mapGetters('funnel', [
            'GetTagsForSelect', 'GetNewTagsForSelect'
        ])
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionAddNewStep', 'ActionUpdateStep'
        ]),
        ...mapActions('step', [
            'ActionSetActionComponent', 'ActionClearState'
        ]),
        newAction(componentType) {
            this.ActionSetActionComponent(componentType)
        },
        saveStep() {
            if (this.isEditingStep) {
                this.ActionUpdateStep({
                    index: this.currentStep,
                    data: { ...this.step, actions: [ ...this.actions ]}
                })
                .then(() => this.clearForm())
            } else {
                this.ActionAddNewStep({ ...this.step, actions: [ ...this.actions ]})
                    .then(() => this.clearForm())
            }
        },
        cancelSaveStep() {
            this.$swal.fire({
                    title: 'Cancelar cadastro do passo?',
                    text: `Os dados informados serão perdidos...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        this.ActionSetShowCrudStep(false)
                    }
                })
        },
        clearForm() {
            this.ActionClearState()
            this.ActionSetShowCrudStep(false)
        },
    },
    mounted() {
        if (this.isEditingStep) {
            Object.assign(this.step, { ...this.$store.state.step })
        }
    }
}
</script>
