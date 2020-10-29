<template>
    <div>
        <customer-list />
    </div>
</template>

<script>
import CustomerList from './CustomerList'
import { mapState } from 'vuex'
export default {
    components: {
        CustomerList
    },
    computed: {
        ...mapState('marketingAction', [
            'isEditingCustomers'
        ])
    }
}
</script>
