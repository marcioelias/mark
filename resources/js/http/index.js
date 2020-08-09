import Vue from 'vue'
import axios from 'axios'

Vue.use({
    install(Vue) {
        Vue.prototype.$http = axios.create({
            baseURL: 'https://mark2.dev.test',
            headers: {
                common: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            },
        })

        Vue.prototype.$http.interceptors.response.use(res => res,
            err => {
                if (err.response.status === 401) {
                    //window.location = '/'
                    window.location.reload()
                }
                Promise.reject(err)
            })
    }
})
