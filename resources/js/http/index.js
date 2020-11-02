import Vue from 'vue'
import axios from 'axios'

Vue.use({
    install(Vue) {
        Vue.prototype.$http = axios.create({
            baseURL: process.env.MIX_APP_URL,
            headers: {
                common: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            },
        })
    }
})
