import Vue from 'vue'
import axios from 'axios'

Vue.use({
    install(Vue) {
        Vue.prototype.$http = axios.create({
            baseURL: 'http://ec2-18-221-128-147.us-east-2.compute.amazonaws.com/',
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
