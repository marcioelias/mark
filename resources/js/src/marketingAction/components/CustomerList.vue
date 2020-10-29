<template>
    <div>
        <customer-filters />
        <div class="table-responsive">
            <table class="table table-sm table-hover bg-white mb-0">
            <thead>
                <tr class="bg-primary">
                    <customer-list-header
                        caption=""
                        field="customer_name">
                        <fieldset>
                        <div class="vs-checkbox-con vs-checkbox-primary">
                            <input type="checkbox" class="mr-1" @click="toggleCustomerCheck" v-model="selectedAll">
                            <span class="vs-checkbox vs-checkbox-sm">
                                <span class="vs-checkbox--check">
                                    <i class="vs-icon feather icon-check"></i>
                                </span>
                            </span>
                            <span class="">Nome</span>
                        </div>
                    </fieldset>

                    </customer-list-header>
                    <customer-list-header
                        caption="E-mail"
                        field="customer_email"
                    />
                    <customer-list-header
                        caption="Telefone"
                        field="customer_phone_number"
                    />
                    <customer-list-header
                        caption="Status"
                        field="customer_status_id"
                    />
                </tr>
            </thead>
            <tbody>
                <tr v-if="customers.length == 0">
                    <td colspan="5" class="text-center">
                        Nenhum dado retornado para os parâmetros especificados
                    </td>
                </tr>
                <tr v-else v-for="customer in GetOrderedCustomers" :key="customer.index">
                <td>
                    <fieldset>
                        <div class="vs-checkbox-con vs-checkbox-primary">
                            <input type="checkbox"
                                :value="customer.id"
                                v-model="customer.checked">
                            <span class="vs-checkbox vs-checkbox-sm">
                                <span class="vs-checkbox--check">
                                    <i class="vs-icon feather icon-check"></i>
                                </span>
                            </span>
                            <span class="">{{ customer.customer_name }}</span>
                        </div>
                    </fieldset>
                </td>
                <td>{{ customer.customer_email }}</td>
                <td>{{ customer.customer_phone_number }}</td>
                <td>{{ customer.customer_status }}</td>
                </tr>
            </tbody>
            </table>
        </div>
        <span v-show="httpErrors.hasOwnProperty('customers')" class="invalid-feedback" style="display: block">
            <span v-for="(error, index) in httpErrors.customers" :key="index">{{ error }}</span>
        </span>
    </div>
</template>

<style lang="css">
    table tbody { display:block; min-height:300px; max-height:450px; overflow-y:scroll; }
    table thead, table tbody tr { display:table; width:100%; table-layout:fixed; }
</style>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import CustomerFilters from './CustomerFilters'
import CustomerListHeader from './CustomerListHeader'
export default {
    data() {
        return {
            selectedAll: false
        }
    },
    computed: {
        ...mapState('marketingAction', [
            'customers',
            'httpErrors'
        ]),
        ...mapGetters('marketingAction', [
            'GetOrderedCustomers'
        ])
    },
    components: {
        CustomerFilters, CustomerListHeader
    },
    methods: {
        ...mapActions('marketingAction', [
            'ActionToggleCustomerSelect'
        ]),
        toggleCustomerCheck() {
            this.ActionToggleCustomerSelect(!this.selectedAll)
        }
    }
}
</script>
