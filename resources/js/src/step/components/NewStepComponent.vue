<template>
    <div>
        <div class="card mb-2" v-if="isSalesFunnel">
            <div class="row">
                <div class="col-md-6">
                    <label for="postback_event_type_id">Tipo de Postback</label>
                    <Select2 v-model="step.postback_event_type_id" name="postback_event_type_id" id="postback_event_type_id" :options="GetPostbackEventTypesForSelect" />
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-primary text-white pl-1 d-flex justify-content-between align-items-center" style="padding: .5rem" v-if="actionComponent === actionComponentTypes.ACTIONS_TABLE">
                Ações
                <action-button @on-add-action="doOnAddActionClick">Nova Ação</action-button>
            </div>
            <div class="card-body p-0">
                <component :is="actionComponent" @new-action-added="saveStep" @action-updated="saveStep" @deleted-action="saveStep"></component>
            </div>
            <div class="card-footer" v-if="actionComponent === actionComponentTypes.ACTIONS_TABLE && isSalesFunnel">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-secondary float-right" @click="cancelSaveStep"><i class="fas fa-times"></i> Cancelar</button>
                        <button class="btn btn-success float-right mr-1" @click="saveStep"><i class="fas fa-save"></i> Salvar evento</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    p-05 {
        padding: .5rem !important;
    }
</style>>

<script>
import Select2 from 'v-select2-component';
import { mapState, mapGetters, mapActions } from 'vuex'
import ActionsTable from '../../action/components/ActionsTable'
import NewSmsAction from '../../action/components/NewSmsAction'
import NewEmailAction from '../../action/components/NewEmailAction'
import NewWhatsappAction from '../../action/components/NewWhatsappAction'
import NewFunnelAction from '../../action/components/NewFunnelAction'
import ActionButton from '../../actionButton/components/ActionButton'
import * as componentTypes from '../../action/component-types'
import { ACTION } from '../../../user/constants'

const iniData = {
        id: null,
        funnel_step_sequence: null,
        postback_event_type_id: null
    }

const originalSteps = {}

export default {
    data() {
        return {
            step: { ...iniData },
            actionComponentTypes: { ...componentTypes }
        }
    },
    props: {
        initialAction: {
            type: String,
            default: null
        }
    },
    components: {
        Select2, ActionsTable, NewSmsAction, NewEmailAction, NewWhatsappAction, ActionButton, NewFunnelAction
    },
    computed: {
        ...mapState('step', [
            'actionComponent', 'actions'
        ]),
        ...mapState('funnel', [
            'steps', 'currentStep', 'isEditingStep', 'isSalesFunnel'
        ]),
        ...mapGetters('funnel', [
            'GetPostbackEventTypesForSelect'
        ])
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionAddNewStep', 'ActionUpdateStep', 'ActionSetCurrentStep', 'ActionSetSteps', 'ActionClearState'
        ]),
        ...mapActions('step', [
            'ActionSetActionComponent', 'ActionClearState',
        ]),
        saveStep() {
            if (this.isEditingStep) {
                this.ActionUpdateStep({
                    index: this.currentStep,
                    data: { ...this.step, actions: [ ...this.actions ]}
                })
                .then(() => {
                    if (this.isSalesFunnel) {
                        this.clearForm()
                    }
                })
            } else {
                this.ActionAddNewStep({ ...this.step, actions: [ ...this.actions ]})
                    .then(() => this.clearForm())
            }
        },
        cancelSaveStep() {
            this.$swal.fire({
                    title: 'Cancelar cadastro do evento?',
                    text: `Os dados informados serão perdidos...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        this.ActionSetCurrentStep(0)
                        this.ActionSetShowCrudStep(false)
                    }
                })
        },
        clearForm() {
            this.ActionClearState()
            this.ActionSetShowCrudStep(false)
        },
        doOnAddActionClick(actionTypeId) {
            switch (actionTypeId) {
                case ACTION.EMAIL:
                    this.ActionSetActionComponent(this.actionComponentTypes.NEW_EMAIL_ACTION)
                    break;
                case ACTION.SMS:
                    this.ActionSetActionComponent(this.actionComponentTypes.NEW_SMS_ACTION)
                    break;
                case ACTION.WHATSAPP:
                    this.ActionSetActionComponent(this.actionComponentTypes.NEW_WHATSAPP_ACTION)
                    break;
                case ACTION.FUNNEL:
                    this.ActionSetActionComponent(this.actionComponentTypes.NEW_FUNNEL_ACTION)
                    break;

            }
        }
    },
    mounted() {
        if (this.isEditingStep) {
            Object.assign(this.step, { ...this.$store.state.step })
            Object.assign(originalSteps, this.steps)
            this.doOnAddActionClick(this.initialAction)
        }
    }
}
</script>
