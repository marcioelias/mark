<template>
    <div>
        <div class="row">
            <div class="col-md-5">
                <label for="product_id">Produto</label>
                <Select2 v-model="myValue" name="product_id" id="product_id" :options="GetProductsForSelect" :settings="{  }" @change="myChangeEvent($event)" @select="mySelectEvent($event)" />
            </div>
            <div class="col-md-5">
                <label for="tag">Tag</label>
                <Select2 v-model="tag" name="tag" id="tag" :setting="{dropdownCssClass: 'form-control'}" :options="GetTagsForSelect" />
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
                        <vs-tab v-for="(step, index) in steps" :key="index" :label="step.name" icon-pack="fas" icon="fa-angle-right" class="p-0">
                            <show-step-component :step="step" />
                        </vs-tab>
                        <vs-tab label="Novo Passo" icon-pack="fas" icon="fa-plus-square" @click="ActionSetShowCrudStep(true)" class="p-0">
                        </vs-tab>
                    </vs-tabs>
                    <new-step-component v-else key="crud" class="mt-1"/>
                </transition>
            </div>
        </div>
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
            active: true,
            myValue: '',
            tag: null,
            myOptions: ['op1', 'op2', 'op3'], // or [{id: key, text: value}, {id: key, text: value}]
        }
    },
    components: {
        Select2, NewStepComponent, ShowStepComponent
    },
    computed: {
        ...mapGetters('funnel', [
            'GetProductsForSelect', 'GetTagsForSelect'
        ]),
        ...mapState('funnel', [
            'showCrudStep', 'steps', 'products'
        ]),
    },
    mounted() {
        this.ActionGetProducts({ vm: this })
        this.ActionGetTags({ vm: this })
    },
    methods: {
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionGetProducts', 'ActionGetTags'
        ]),
    }
}
</script>
