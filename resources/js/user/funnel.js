import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import VueSweetAlert2 from 'vue-sweetalert2'
import FunnelComponent from '../src/funnel/components/FunnelComponent'

import 'vuesax/dist/vuesax.css'

import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme

Vue.use(VueQuillEditor)



Vue.use(Vuesax, {})
Vue.use(VueSweetAlert2)

import '../http'

export default new Vue({
    el: '#funnel',
    store,
    components: {
        FunnelComponent
    }
})
