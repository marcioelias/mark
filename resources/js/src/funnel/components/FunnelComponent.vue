<template>
    <div>
        <div class="row">
            <div class="col-md-5">
                <label for="product_id">Produto</label>
                <template v-if="isEditing">
                    <div class="form-control">{{ productName }}</div>
                </template>
                <template v-else>
                    <Select2 v-model="funnelProduct" name="product_id" id="product_id" :options="GetProductsForSelect" :settings="{  }" />
                    <div class="invalid-feedback"  id="error-product_id"></div>
                    <span v-show="httpErrors.hasOwnProperty('product_id')" class="invalid-feedback" style="display: block">
                        <span v-for="(error, index) in httpErrors.product_id" :key="index">{{ error }}</span>
                    </span>
                </template>
            </div>
            <div class="col-md-5">
                <label for="tag">Tag</label>
                <template v-if="isEditing">
                    <div class="form-control">{{ tagName }}</div>
                </template>
                <template v-else>
                    <Select2 v-model="funnelTag" name="tag" id="tag" :setting="{dropdownCssClass: 'form-control'}" :options="GetTagsForSelect" />
                    <span v-show="httpErrors.hasOwnProperty('tag_id')" class="invalid-feedback" style="display: block">
                        <span v-for="(error, index) in httpErrors.tag_id" :key="index">{{ error }}</span>
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
            <div class="card-header p-0 mt-1" v-if="steps.length == 0 && !showCrudStep">
                <button class="btn btn-success float-right" @click="ActionSetShowCrudStep(true)"><i class="fas fa-plus"></i> Adicionar Primeiro Passo</button>
            </div>
            <div class="card-body p-0" v-if="showCrudStep || steps.length > 0">
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in" apper>
                    <vs-tabs v-if="!showCrudStep" key="tabs" v-model="currentStepIndex">
                        <vs-tab v-for="step in OrderedSteps" :key="step.funnel_step_sequence" :label="step.funnel_step_description" icon-pack="fas" icon="fa-angle-right" class="p-0">
                            <step-component :step="step" />
                        </vs-tab>
                        <vs-tab label="Novo Passo" icon-pack="fas" icon="fa-plus-square" @click="addNewStep()" class="p-0">
                        </vs-tab>
                    </vs-tabs>
                    <new-step-component v-else key="crud" class="mt-1"/>
                </transition>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg fixed-bottom p-0 mt-1" v-if="!showCrudStep">
            <div class="ml-auto">
                <button type="button" @click="saveFunnel" class="btn btn-primary waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Salvar">
                    <i class="fa fa-check"></i>
                </button>
                <button type="button" @click="cancelFunnel" class="btn btn-danger waves-effect waves-light" data-toggle="tooltip" data-placement="top" title="Cancelar">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </nav>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
import NewStepComponent from '../../step/components/NewStepComponent'
import StepComponent from '../../step/components/StepComponent'
import { mapGetters, mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
           currentStepIndex: 0,
           isEditing: false,
           response: {}
        }
    },
    props: {
        funnelId: {
            type: String,
            default: null
        }
    },
    components: {
        Select2, NewStepComponent, StepComponent
    },
    watch: {
        currentStepIndex: function(value) {
            this.ActionSetCurrentStep(value)
        }
    },
    computed: {
        ...mapGetters('funnel', [
            'GetProductsForSelect', 'GetTagsForSelect', 'GetNewTagsForSelect', 'OrderedSteps', 'GetProductById', 'GetTagById'
        ]),
        ...mapState('funnel', [
            'showCrudStep', 'steps', 'products', 'tag', 'product', 'active', 'httpErrors'
        ]),
        funnelTag: {
            get() {
                return this.tag
            },
            set(value) {
                this.ActionSetTag(value)
            }
        },
        funnelProduct: {
            get() {
                return this.product
            },
            set(value) {
                this.ActionSetProduct(value)
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
        productName() {
            return this.product && this.GetProductById(this.product).product_name
        },
        tagName() {
            return this.tag && this.GetTagById(this.tag).tag_name
        }
    },
    mounted() {
        this.loadData()
            .then(() => {
                if (this.funnelId) {
                    this.isEditing = true
                    this.ActionLoadFunnel({ vm: this, id: this.funnelId })
                }
            })
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionGetProducts', 'ActionGetTags', 'ActionClearState',
            'ActionSetTag', 'ActionSetProduct', 'ActionSaveFunnel', 'ActionGetActionTypes',
            'ActionLoadFunnel', 'ActionSetActive', 'ActionSetCurrentStep', 'ActionSetHttpErrors'
        ]),
        ...mapActions('variables', [
            'ActionGetVariablesFromApi'
        ]),
        addNewStep() {
            this.ActionSetShowCrudStep(true)
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
                            }).then(() => window.location =  r.data.redirect)
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
        loadData() {
            return new Promise((resolve, reject) => {
                this.ActionGetProducts({ vm: this })
                this.ActionGetTags({ vm: this })
                this.ActionGetVariablesFromApi({ vm: this })
                this.ActionGetActionTypes({ vm: this })
                resolve()
            })
        },
        validateSteps() {
            if (this.steps.length < 1) {
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
                return true
            }
        }
    }
}
</script>
