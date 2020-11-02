import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import VueSweetAlert2 from 'vue-sweetalert2'
import FormComponent from '../src/customersImport/components/FormComponent.vue'

import 'vuesax/dist/vuesax.css'

Vue.use(Vuesax, {})
Vue.use(VueSweetAlert2)

import '../http'

export default new Vue({
    el: '#customers-import',
    store,
    components: {
        FormComponent
    }
})
