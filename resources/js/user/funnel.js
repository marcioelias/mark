import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import FunnelComponent from '../funnel/component/FunnelComponent'

import 'vuesax/dist/vuesax.css'

Vue.use(Vuesax, {})

import '../http'

export default new Vue({
    el: '#funnel',
    store,
    components: {
        FunnelComponent
    }
})
