import Vue from 'vue'
import store from '../store'
import FunnelOrgChart from '../src/funnelOrgChart/FunnelOrgChart.vue'

import '../http'


export default new Vue({
    el: '#funnel-show',
    store,
    components: {
        FunnelOrgChart
    }
})
