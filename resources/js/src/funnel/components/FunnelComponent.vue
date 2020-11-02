<template>
    <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in" apper>
        <div v-if="loading" class="d-flex justify-content-center align-items-center" key="loadingContent">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Carregando...</span>
            </div>
            <span class="pl-2">Carregando...</span>
        </div>
        <div v-else key="loadedContent">
            <div class="row">
                <div class="col-md-10">
                    <template v-if="isEditing">
                        <template v-if="isSalesFunnel">
                        <label for="product_id">Descrição (Venda)</label>
                        </template>
                        <template v-else>
                        <label for="product_id">Descrição (Remarketing)</label>
                        </template>
                        <div class="form-control">{{ description }}</div>
                    </template>
                    <template v-else>
                        <label for="product_id">Descrição</label>
                        <input type="text" class="form-control" v-model="funnelDescription" name="description" id="description" />
                        <div class="invalid-feedback"  id="error-description"></div>
                        <span v-show="httpErrors.hasOwnProperty('description')" class="invalid-feedback" style="display: block">
                            <span v-for="(error, index) in httpErrors.description" :key="index">{{ error }}</span>
                        </span>
                    </template>
                </div>
                <div class="col-md-2">
                    <label>Ativo</label>
                    <div class="custom-control custom-switch switch-md custom-switch-primary mr-2 mb-1" style="margin-top: 0.6rem;">
                        <input type="checkbox" name="active" class="custom-control-input" id="switch-active" v-model="funnelActive">
                        <label class="custom-control-label" for="switch-active">
                            <span class="switch-icon-left"><i class="feather icon-check"></i></span>
                            <span class="switch-icon-right"><i class="feather icon-check"></i></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="card mb-0">
                <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in" apper>
                <div class="card-header p-0 mt-3 d-flex justify-content-around" v-if="steps.length == 0 && !showCrudStep" key="havent_steps">
                    <div class="card border-success text-center bg-transparent">
                        <div class="card-content d-flex">
                            <div class="card-body">
                                <p>
                                    <i class="fas fa-funnel-dollar fa-3x text-success"></i>
                                </p>
                                <h4>Funil de Venda</h4>
                                <button class="btn btn-success float-right" @click="addFirstStep"><i class="fas fa-plus"></i> Adicionar Primeiro Passo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card border-success text-center bg-transparent">
                        <div class="card-content d-flex">
                            <div class="card-body">
                                <p>
                                    <i class="fas fa-filter fa-3x text-success"></i>
                                </p>
                                <h4>Funil de Remarketing</h4>
                                <action-button @on-add-action="addFirstAction">Adicionar primeira Ação</action-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0" v-else key="has_steps">
                    <template v-if="isSalesFunnel">
                        <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in" apper>
                            <vs-tabs v-if="!showCrudStep" key="tabs" v-model="currentStepIndex">
                                <vs-tab v-for="step in OrderedSteps" :key="step.funnel_step_sequence" :label="GetPostbackEventTypeById(step.postback_event_type_id).postback_event_type" icon-pack="fas" icon="fa-angle-right" class="p-0">
                                    <step-component :step="step" />
                                </vs-tab>
                                <vs-tab label="Novo Passo" icon-pack="fas" icon="fa-plus-square" @click="addNewStep()" class="p-0">
                                </vs-tab>
                            </vs-tabs>
                            <new-step-component v-else key="crud" class="mt-1"/>
                        </transition>
                    </template>
                    <template v-else>
                        <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in" apper>
                            <new-step-component :initial-action="firstAction" key="crud" class="mt-1" />
                        </transition>
                    </template>

                </div>
                </transition>
            </div>
            <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster">
            <nav class="navbar navbar-expand-lg fixed-bottom p-0 mt-1" v-if="!showCrudStep || (!isSalesFunnel &&  !showCrudAction)">
                <div class="ml-auto">
                    <button type="button" @click="saveFunnel" class="btn btn-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Salvar">
                        <i class="fa fa-check"></i>
                    </button>
                    <button type="button" @click="cancelFunnel" class="btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </nav>
            </transition>
        </div>
    </transition>
</template>

<script>
import Select2 from 'v-select2-component';
import NewStepComponent from '../../step/components/NewStepComponent'
import StepComponent from '../../step/components/StepComponent'
import { mapGetters, mapState, mapActions } from 'vuex'
import ActionButton from '../../actionButton/components/ActionButton'

export default {
    data() {
        return {
            firstAction: null,
            //currentStepIndex: 0,
            isEditing: false,
            response: {},
        }
    },
    props: {
        funnelId: {
            type: String,
            default: null
        }
    },
    components: {
        Select2, NewStepComponent, StepComponent, ActionButton
    },
    /* watch: {
        currentStepIndex: function(value) {
            this.ActionSetCurrentStep(value)
        }
    }, */
    computed: {
        ...mapGetters('funnel', [
            'GetLeadStatusesForSelect', 'OrderedSteps', 'GetPostbackEventTypeById'
        ]),
        ...mapState('funnel', [
            'showCrudStep', 'currentStep', 'showCrudAction', 'steps', 'description', 'isSalesFunnel', 'active', 'httpErrors', 'isLoading'
        ]),
        currentStepIndex: {
            get() {
                return this.currentStep ?? 0
            },
            set(value) {
                this.ActionSetCurrentStep(value)
            }
        },
        funnelDescription: {
            get() {
                return this.description
            },
            set(value) {
                this.ActionSetDescription(value)
            }
        },
        funnelActive: {
            get() {
                return this.active
            },
            set(value) {
                this.ActionSetActive(value)
            }
        },
        loading: {
            get() {
                return this.isLoading
            },
            set(value) {
                this.ActionSetIsLoading(value)
            }
        },
        LeadStatusName() {
            return this.leadStatusId && this.GetLeadStatusById(this.leadStatusId).status
        }
    },
    async mounted() {
        await this.loadData()
        if (this.funnelId) {
            this.isEditing = true
            await this.ActionLoadFunnel({ vm: this, id: this.funnelId })
            if (!this.isSalesFunnel) {
                this.currentStepIndex = 0
                this.ActionLoadStep()
                    .then(() => {
                        this.ActionIsEditingStep(true)
                        this.ActionSetShowCrudStep(true)
                    })
            }
        } else {
            this.loading = false
        }
    },
    created() {
        this.loading = true
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionClearState', 'ActionSetDescription',
            'ActionSaveFunnel', 'ActionGetActionTypes', 'ActionSetIsSalesFunnel',
            'ActionLoadFunnel', 'ActionSetActive', 'ActionSetCurrentStep', 'ActionSetHttpErrors',
            'ActionSetIsLoading', 'ActionGetPostbackEventTypes', 'ActionAddNewStep', 'ActionIsEditingStep'
        ]),
        ...mapActions('step', [
            'ActionLoadStep'
        ]),
        ...mapActions('variables', [
            'ActionGetVariablesFromApi'
        ]),
        addNewStep() {
            this.ActionSetShowCrudStep(true)
        },
        addFirstStep() {
            this.ActionSetIsSalesFunnel(true)
            this.ActionSetShowCrudStep(true)
        },
        addFirstAction(actionId) {
            this.firstAction = actionId
            this.ActionSetIsSalesFunnel(false)
            this.ActionAddNewStep({actions: []})
            this.ActionLoadStep()
                .then(() => {
                    this.ActionIsEditingStep(true)
                    this.ActionSetShowCrudStep(true)
                })
        },
        cancelFunnel() {
            this.$swal.fire({
                    title: 'Cancelar cadastro do Funil?',
                    text: `Os dados informados serão perdidos...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        this.loading = true
                        this.ActionClearState()
                        window.location = '/funnel'
                    }
                })
        },
        saveFunnel() {
            if (this.validateSteps()) {
                this.ActionSaveFunnel({ vm: this })
                    .then(res => {
                        if (res.status === 200) {
                            this.$swal.fire({
                                title: 'Sucesso!',
                                text: 'Registro incluído.',
                                icon: 'success',
                                confirmButtonText: 'Ok',
                                padding: '2em'
                            }).then(() => window.location =  res.data.redirect)
                        }
                    })
                    .catch(err => {
                        switch (err.response.status) {
                            case 422:
                                this.$swal.fire({
                                    title: 'Ooops!',
                                    text: 'Algo deu errado.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok',
                                    padding: '2em'
                                }).then(() => this.ActionSetHttpErrors(err.response.data.errors))
                                break

                            default:
                                this.$swal.fire({
                                    title: 'Ooops!',
                                    text: 'Algo deu errado.',
                                    icon: 'error',
                                    confirmButtonText: 'Ok',
                                    padding: '2em'
                                })
                                break
                        }
                    })
            }
        },
        async loadData() {
            try {
                await this.ActionGetVariablesFromApi({ vm: this })
                await this.ActionGetActionTypes({ vm: this })
                await this.ActionGetPostbackEventTypes({ vm: this })
            } catch (err) {
                console.log(err)
            }
        },
        validateSteps() {
            if (this.steps.length < 1) {
                if (this.isSalesFunnel) {
                    this.$swal.fire({
                            title: 'Nenhum passo adicionado!',
                            text: `Antes de salvar o Funil deve ter ao menos 1 passo.`,
                            icon: 'info',
                            heightAuto: false,
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                        })
                    return false
                } else {
                    this.$swal.fire({
                            title: 'Nenhuma ação adicionada!',
                            text: `Antes de salvar o Funil deve ter ao menos 1 ação.`,
                            icon: 'info',
                            heightAuto: false,
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                        })
                    return false
                }
            } else {
                if (!this.description) {
                    this.$swal.fire({
                            title: 'Descrição não informada',
                            text: `Informe uma descrição antes de salvar o Funil.`,
                            icon: 'info',
                            heightAuto: false,
                            showCancelButton: false,
                            confirmButtonText: 'Ok',
                        })
                    return false
                }
                return true
            }
        }
    }
}
</script>
