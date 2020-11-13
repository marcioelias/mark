import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import VueSweetAlert2 from 'vue-sweetalert2'
import VEmojiPicker from 'v-emoji-picker'
import { v4 as uuidv4 } from 'uuid'
import FunnelComponent from '../src/funnel/components/FunnelComponent'

import 'vuesax/dist/vuesax.css'

import VueQuillEditor from 'vue-quill-editor'

import 'quill/dist/quill.core.css' // import styles
import 'quill/dist/quill.snow.css' // for snow theme
import 'quill/dist/quill.bubble.css' // for bubble theme

Vue.use(VueQuillEditor)
Vue.use(Vuesax, {})
Vue.use(VueSweetAlert2)
Vue.use(VEmojiPicker)
Vue.prototype.$uuid = () => uuidv4()

import '../http'

export default new Vue({
    el: '#funnel',
    store,
    components: {
        FunnelComponent
    }
})
