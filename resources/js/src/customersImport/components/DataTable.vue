<template>
    <div class="table-responsive" v-if="GetCustomersForImport.length > 0">
        <table class="table table-condensed table-striped">
            <thead>
                <tr class="bg-primary text-white">
                    <!-- <th > -->
                        <data-table-header :field="idx-1" v-for="idx in GetCustomersForImport[0].length" :key="idx">
                            <select class="form-control mr-1" @change="onChangeColumn(idx-1)" v-model="columns[idx-1]">
                                <option value=""></option>
                                <option value="customer_name">Nome</option>
                                <option value="customer_email">E-mail</option>
                                <option value="customer_phone_number">Telefone</option>
                            </select>
                        </data-table-header>
                        <!-- <span class="d-flex justify-content-between align-items-center">
                            <select name="" id="" class="form-control mr-1" @change="onChangeColumn(idx-1)" v-model="columns[idx-1]">
                                <option value="customer_name">Nome</option>
                                <option value="customer_email">E-mail</option>
                                <option value="customer_phone_number">Telefone</option>
                            </select>
                            <i class="feather icon-code"></i>
                        </span> -->
                    <!-- </th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="(customer, idx) in GetCustomersForImport" :key="idx">
                    <td v-for="item in customer" :key="item.index">{{ item.content }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style lang="css">
    table tbody { display:block; min-height:300px; max-height:450px; overflow-y:scroll; }
    table thead, table tbody tr { display:table; width:100%; table-layout:fixed; }
</style>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import DataTableHeader from './DataTableHeader'
export default {
    data() {
        return {
            columns: [],
        }
    },
    components: {
        DataTableHeader
    },
    computed: {
        // ...mapState('customersImport', [
        //     'customers'
        // ]),
        ...mapGetters('customersImport', [
            'GetCustomersForImport'
        ])
    },
    methods: {
        ...mapActions('customersImport', [
            'ActionSetColumnOfData'
        ]),
        onChangeColumn(idx) {
            this.ActionSetColumnOfData({
                index: idx,
                column: this.columns[idx]
            })
        }
    }
}
</script>
