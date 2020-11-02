<template>
    <div>
        <div class="row">
            <div class="col-sm-12 col-md-4 mb-1">
                <label>Status do Cliente</label>
                <Select2 v-model="currentCustomerStatus"
                        name="filter_status_client"
                        id="filter_status_client"
                        :options="GetCustomerStatusesForSelect"
                        :settings="{allowClear: true}" />
            </div>
            <div class="col-sm-12 col-md-4 mb-1">
                <label>Última Compra - Início</label>
                <datepicker
					v-model="currentDtLastLeadBegin"
					name="dt_buy_start"
					input-class="form-control"
					wrapper-class="mb-1"
					:language="ptBR"
					format="dd/MM/yyyy"
                    :disabledDates="{
						from: currentDtLastLeadEnd || new Date(),
					}"

                    :clear-button="true"
                    clear-button-icon="fa fa-times"
                    :bootstrap-styling="true">
				</datepicker>
            </div>
            <div class="col-sm-12 col-md-4 mb-1">
                <label>Última Compra - Fim</label>
                <datepicker
					v-model="currentDtLastLeadEnd"
					name="dt_buy_end"
					input-class="form-control"
					wrapper-class="mb-1"
					:language="ptBR"
					format="dd/MM/yyyy"
                    :disabledDates="{
						to: currentDtLastLeadBegin
					}"
                    :clear-button="true"
                    clear-button-icon="fa fa-times"
                    :bootstrap-styling="true">
				</datepicker>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 mb-1">
                <label>Forma de Pagamento</label>
                <Select2 v-model="currentPaymentTypeId"
                        name="filter_payment_way"
                        id="filter_payment_way"
                        :options="GetPaymentTypesForSelect"
                        :settings="{allowClear: true}" />
            </div>
            <div class="col-sm-12 col-md-6 mb-1">
                <label>Produto adquirido</label>
                <Select2 v-model="currentProductId"
                        name="filter_product_buy"
                        id="filter_product_buy"
                        :options="GetProductsForSelect"
                        :settings="{allowClear: true}" />
            </div>
        </div>
        <div class="row">
            <div class="col">
                <button class="btn btn-primary mb-1 float-right" @click="getCustomers"><i class="fa fa-search"></i> Buscar Clientes</button>
            </div>
        </div>
    </div>
</template>

<script>
import Select2 from 'v-select2-component'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import { mapActions, mapGetters, mapState } from 'vuex'

const iniFilters = () => {
	return {
		customerStatus: null,
		dtBegin: null,
		dtEnd: null,
		paymentType: null,
		productId: null
	}
}

export default {
    data() {
        return {
            ptBR: ptBR,
        }
    },
    components: {
        Select2, Datepicker
    },
    computed: {
        ...mapGetters('marketingAction', [
            'GetProductsForSelect',
            'GetCustomerStatusesForSelect',
            'GetPaymentTypesForSelect'
        ]),
        ...mapState('marketingAction', [
            'filters'
        ]),
        currentCustomerStatus: {
            get() {
                return this.filters.customerStatus
            },
            set(value) {
                this.ActionSetFilterCustomerStatus(value)
            }
        },
        currentDtLastLeadBegin: {
            get() {
                return this.filters.dtLastLeadBegin
            },
            set(value) {
                this.ActionSetFilterDtLastLeadBegin(value)
            }
        },
        currentDtLastLeadEnd: {
            get() {
                return this.filters.dtLastLeadEnd
            },
            set(value) {
                this.ActionSetFilterDtLastLeadEnd(value)
            }
        },
        currentProductId: {
            get() {
                return this.filters.productId
            },
            set(value) {
                this.ActionSetFilterProductId(value)
            }
        },
        currentPaymentTypeId: {
            get() {
                return this.filters.paymentTypeId
            },
            set(value) {
                this.ActionSetFilterPaymentTypeId(value)
            }
        }
    },
    mounted() {
        //just for the create method
        this.ActionGetCustomerStatuses({ vm: this })
        this.ActionGetPaymentTypes({ vm: this })
    },
    methods: {
        ...mapActions('marketingAction', [
            'ActionSetFilterCustomerStatus',
            'ActionSetFilterDtLastLeadBegin',
            'ActionSetFilterDtLastLeadEnd',
            'ActionSetFilterProductId',
            'ActionSetFilterPaymentTypeId',
            'ActionGetCustomerStatuses',
            'ActionGetPaymentTypes',
            'ActionSearchCustomers'
        ]),
        getCustomers() {
            this.ActionSearchCustomers({ vm: this })
        }
    }
}
</script>
