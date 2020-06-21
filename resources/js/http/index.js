import Vue from 'vue'
import axios from 'axios'

axios.defaults.baseURL = 'https://mark2.dev.test'  //process.env.APP_URL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

Vue.use({
    install(Vue) {
        Vue.prototype.$http = axios
    }
})
