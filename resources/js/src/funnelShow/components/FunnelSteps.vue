<template>
    <div class="mb-0 mt-1">
        <vs-tabs v-model="currentStepIndex">
            <vs-tab v-for="step in OrderedSteps" :key="step.funnel_step_sequence" :label="step.funnel_step_description" icon-pack="fas" icon="fa-angle-right" class="p-0">
                <funnel-step
                    :step="step"
                />
            </vs-tab>
        </vs-tabs>
    </div>
</template>

<script>
import FunnelStep from './FunnelStep'

export default {
    data() {
        return {
            currentStepIndex: 0
        }
    },
    props: {
        steps: {
            type: Array,
            required:  true
        }
    },
    components: {
        FunnelStep
    },
    computed: {
        OrderedSteps() {
            return this.steps.sort((a, b) => a.funnel_step_sequence - b.funnel_step_sequence)
        }
    }
}
</script>
