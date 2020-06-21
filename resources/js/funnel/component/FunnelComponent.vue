<template>
    <div>
        <div class="row">
            <div class="col-10">
                <label for="product_id">Produto</label>
                <Select2 v-model="myValue" name="product_id" id="product_id" :options="GetProductsForSelect" :settings="{  }" @change="myChangeEvent($event)" @select="mySelectEvent($event)" />
            </div>
            <div class="col-2">
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
        <vs-tabs class="mt-2 border-2">
            <vs-tab v-for="step in steps" :key="step.id" :label="step.name" class="p-0">
                <show-step-component />
            </vs-tab>
            <vs-tab label="Novo Passo" icon-pack="fa" icon="fa-plus">
                <div>
                    <new-step-component />
                </div>
            </vs-tab>
        </vs-tabs>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
import NewStepComponent from './NewStepComponent'
import ShowStepComponent from './ShowStepComponent'
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            active: true,
            myValue: '',
            myOptions: ['op1', 'op2', 'op3'], // or [{id: key, text: value}, {id: key, text: value}]
            steps: [
                {
                    id: '123',
                    name: 'Passo 1',
                    data: [
                        {
                            id: 1,
                            name: 'Enviar SMS'
                        },
                        {
                            id: 2,
                            name: 'Enviar E-mail'
                        }
                    ]
                }
            ]
        }
    },
    components: {
        Select2, NewStepComponent, ShowStepComponent
    },
    computed: {
        ...mapGetters('funnel', [
            'GetProductsForSelect'
        ]),
        products() {
            return this.$store.funnel.state.products
        }
    },
    mounted() {
        this.$store.dispatch('funnel/ActionGetProducts', { vm: this })
    },
    methods: {
        myChangeEvent(val){
            console.log(val);
        },
        mySelectEvent({id, text}){
            console.log({id, text})
        }
    }
}
</script>
