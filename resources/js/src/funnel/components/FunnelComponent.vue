<template>
    <div>
        <div class="row">
            <div class="col-md-5">
                <label for="product_id">Produto</label>
                <Select2 v-model="funnelProduct" name="product_id" id="product_id" :options="GetProductsForSelect" :settings="{  }" />
            </div>
            <div class="col-md-5">
                <label for="tag">Tag</label>
                <Select2 v-model="funnelTag" name="tag" id="tag" :setting="{dropdownCssClass: 'form-control'}" :options="GetTagsForSelect" />
            </div>
            <div class="col-md-2">
                <label>Ativo</label>
                <div class="custom-control custom-switch switch-md custom-switch-primary mr-2 mb-1" style="margin-top: 0.6rem;">
                    <input type="checkbox" name="active" class="custom-control-input" id="switch-active" v-model="active">
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
                    <vs-tabs v-if="!showCrudStep" key="tabs">
                        <vs-tab v-for="step in OrderedSteps" :key="step.funnel_step_sequence" :label="step.funnel_step_description" icon-pack="fas" icon="fa-angle-right" class="p-0">
                            <show-step-component :step="step" />
                        </vs-tab>
                        <vs-tab label="Novo Passo" icon-pack="fas" icon="fa-plus-square" @click="ActionSetShowCrudStep(true)" class="p-0">
                        </vs-tab>
                    </vs-tabs>
                    <new-step-component v-else key="crud" class="mt-1"/>
                </transition>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg fixed-bottom p-0 mt-1" v-if="!showCrudStep && steps.length > 0">
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
import NewStepComponent from '../../steps/components/NewStepComponent'
import ShowStepComponent from '../../steps/components/ShowStepComponent'
import { mapGetters, mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
            isEditing: false,
            active: true,
        }
    },
    props: {
        funnelId: {
            type: String,
            default: null
        }
    },
    components: {
        Select2, NewStepComponent, ShowStepComponent
    },
    computed: {
        ...mapGetters('funnel', [
            'GetProductsForSelect', 'GetTagsForSelect', 'GetNewTagsForSelect', 'OrderedSteps'
        ]),
        ...mapState('funnel', [
            'showCrudStep', 'steps', 'products', 'tag', 'product'
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
        }
    },
    mounted() {
        try {
            this.ActionGetProducts({ vm: this })
            this.ActionGetTags({ vm: this })
            this.ActionGetVariablesFromApi({ vm: this })
            this.ActionGetActionTypes({ vm: this })
            this.isEditing = (this.funnelId)
        } finally {
            if (this.isEditing) {
                this.ActionLoadFunnel({ vm: this, id: this.funnelId })
                this.ActionLoadActions()
            }
        }
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionGetProducts', 'ActionGetTags', 'ActionClearState',
            'ActionSetTag', 'ActionSetProduct', 'ActionSaveFunnel', 'ActionGetActionTypes',
            'ActionLoadFunnel'
        ]),
        ...mapActions('variables', [
            'ActionGetVariablesFromApi'
        ]),
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
            this.ActionSaveFunnel({ vm: this })
        }
    }
}
</script>
