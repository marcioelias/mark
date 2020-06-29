import Vue from 'vue'
import store from '../store'
import Vuesax from 'vuesax'
import VueSweetAlert2 from 'vue-sweetalert2'
import FunnelComponent from '../src/funnel/components/FunnelComponent'

import 'vuesax/dist/vuesax.css'

// Require Froala Editor js file.
//require('froala-editor/js/froala_editor.pkgd.min.js')
import 'froala-editor/js/froala_editor.pkgd.min.js'
/* import 'froala-editor/js/plugins/link.min.js'
import 'froala-editor/js/plugins/image.min.js' */
import 'froala-editor/js/plugins.pkgd.min.js';
import 'froala-editor/js/plugins/font_family.min.js';


// Require Froala Editor css files.
require('froala-editor/css/froala_editor.pkgd.min.css')
require('froala-editor/css/froala_style.min.css')

// Import and use Vue Froala lib.
import VueFroala from 'vue-froala-wysiwyg'

Vue.use(VueFroala)

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
