<template>
    <div>
        <marketing-action-header />
        <div class="divider divider-primary divider-dotted">
            <div class="divider-text">Clientes</div>
        </div>
        <marketing-action-body />
        <marketing-action-footer />
    </div>
</template>

<style lang="css">
    .select2 {
        width: 100% !important;
    }
</style>

<script>
import MarketingActionHeader from './MarketingActionHeader'
import MarketingActionBody from './MarketingActionBody'
import MarketingActionFooter from './MarketingActionFooter'
export default {
    components: {
        MarketingActionHeader,
        MarketingActionBody,
        MarketingActionFooter
    }
}
</script>
