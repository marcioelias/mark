<template>
    <div>
        <step-leads :leads-data="leads" :step-id="step.id" />
    </div>
</template>

<script>
import StepLeads from './StepLeads'

export default {
    data() {
        return {
            leads: {}
        }
    },
    props: {
        step: {
            type: Object,
            required: true
        }
    },
    components: {
        StepLeads
    }
}
</script>
