import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import VueSweetAlert2 from 'vue-sweetalert2'
import FunnelShowComponent from '../src/funnelShow/components/FunnelShowComponent'
import Moment from 'moment'
import Vue2Filters from 'vue2-filters'

import 'vuesax/dist/vuesax.css'

import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme

var Vue2FiltersConfig = {
    capitalize: {
        onlyFirstLetter: false
    },
    number: {
        format: '0',
        thousandsSeparator: '.',
        decimalSeparator: ','
    },
    bytes: {
        decimalDigits: 2
    },
    percent: {
        decimalDigits: 2,
        multiplier: 100
    },
    currency: {
        symbol: 'R$ ',
        decimalDigits: 2,
        thousandsSeparator: '.',
        decimalSeparator: ',',
        symbolOnLeft: true,
        spaceBetweenAmountAndSymbol: false,
        showPlusSign: false
    },
    pluralize: {
        includeNumber: false
    },
    ordinal: {
        includeNumber: false
    }
}

Vue.use(VueQuillEditor)
Vue.use(Vuesax, {})
Vue.use(VueSweetAlert2)
Vue.use(Vue2Filters, Vue2FiltersConfig)

Vue.filter('formatDateTime', (value) => {
    if (value) {
        return Moment(String(value)).format('DD/MM/YYYY hh:mm:ss')
    }
})

Vue.directive('tooltip', (el, binding = 'auto') => {
    $(el).tooltip({
        title: binding.value,
        placement: binding.arg,
        trigger: 'hover'
    })
})

import '../http'


export default new Vue({
    el: '#funnel-show',
    store,
    mixins: [
        Vue2Filters.mixin
    ],
    components: {
        FunnelShowComponent
    }
})
