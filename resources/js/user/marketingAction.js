import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import VueSweetAlert2 from 'vue-sweetalert2'
import moment from 'moment'
import VEmojiPicker from 'v-emoji-picker'
import MarketingAction from '../src/marketingAction/components/MarketingAction.vue'

import 'vuesax/dist/vuesax.css'

import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme

Vue.use(VueQuillEditor)
Vue.use(Vuesax, {})
Vue.use(VueSweetAlert2)
Vue.use(VEmojiPicker)
Vue.prototype.$moment = moment

import '../http'

export default new Vue({
    el: '#marketing-action-container',
    store,
    components: {
        MarketingAction
    }
})
