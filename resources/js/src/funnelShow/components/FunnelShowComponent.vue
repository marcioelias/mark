<template>
    <div>
        <funnel-header
            :productName="funnel.product.product_name"
            :tagName="funnel.tag.tag_name"
            :active="(funnel.active === 1)"
        />
        <funnel-steps
            :steps="funnel.steps"
        />
        <show-notification-sent />
    </div>
</template>

<script>
import { mapState } from 'vuex'
import FunnelHeader from './FunnelHeader'
import FunnelSteps from './FunnelSteps'
import ShowNotificationSent from './ShowNotificationSent'

export default {
    props: {
        funnel: {
            type: Object,
            required: true
        }
    },
    components: {
        FunnelHeader, FunnelSteps, ShowNotificationSent
    },
    watch: {
        currentSchedule(value) {
            console.log(this)
            value ? $(this.$children[2].$refs['notificationSentModal']).modal('show') : ''
        }
    },
    computed: {
        ...mapState('funnelShow', [
            'currentSchedule'
        ])
    }
}
</script>
