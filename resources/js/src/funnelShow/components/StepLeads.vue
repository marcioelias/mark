<template>
    <transition enter-active-class="animate__animated animate__fadeIn animate__faster" leave-active-class="animate__animated animate__fadeOut animate__faster" mode="out-in" apper>
        <div v-if="isLoading" class="d-flex justify-content-center align-items-center p-2" key="loadingContent">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Carregando...</span>
            </div>
            <span class="pl-2">Carregando...</span>
        </div>
        <div class="mt-1" v-else key="displayContent">
            <div class="row mb-1 d-flex justify-content-between">
                <div class="col flex-grow-0 d-flex align-items-end ml-1 mr-2">
                    <h4 class="text-gray">Leads</h4>
                </div>
                <div class="col flex-grow-1">
                    <fieldset class="position-relative has-icon-left input-divider-left">
                        <div class="input-group">
                            <input
                                type="text"
                                name="search_by"
                                class="form-control"
                                id="search_by"
                                v-model="searchString"
                                placeholder="Buscar por..."
                            />
                            <div class="input-group-append">
                                <button class="btn btn-primary icon-btn-sm-padding" @click="getResults()">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-control-position">
                            <i class="feather icon-search"></i>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm" :class="{'table-hover': leads.data.length}">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col" :class="{'bg-warning': orderBy.column == 'leads.created_at'}">
                                <a class="d-flex align-items-center justify-content-between"
                                    @click="setOrderBy('leads.created_at')">
                                    <span>Data da Compra</span>
                                    <i class="feather" :class="getOrderIcon('leads.created_at')"></i>
                                </a>
                            </th>
                            <th scope="col" :class="{'bg-warning': orderBy.column == 'leads.transaction_code'}">
                                <a class="d-flex align-items-center justify-content-between"
                                    @click="setOrderBy('leads.transaction_code')">
                                    <span>Transação</span>
                                    <i class="feather" :class="getOrderIcon('leads.transaction_code')"></i>
                                </a>
                            </th>
                            <th scope="col" :class="{'bg-warning': orderBy.column == 'customers.customer_name'}">
                                <a class="d-flex align-items-center justify-content-between"
                                    @click="setOrderBy('customers.customer_name')">
                                    <span>Cliente</span>
                                    <i class="feather" :class="getOrderIcon('customers.customer_name')"></i>
                                </a>
                            </th>
                            <th scope="col" :class="{'bg-warning': orderBy.column == 'leads.value'}">
                                <a class="d-flex align-items-center justify-content-between"
                                    @click="setOrderBy('leads.value')">
                                    <span>Valor</span>
                                    <i class="feather" :class="getOrderIcon('leads.value')"></i>
                                </a>
                            </th>
                            <th scope="col" :class="{'bg-warning': orderBy.column == 'lead_statuses.status'}">
                                <a class="d-flex align-items-center justify-content-between"
                                    @click="setOrderBy('lead_statuses.status')">
                                    <span>Status</span>
                                    <i class="feather" :class="getOrderIcon('lead_statuses.status')"></i>
                                </a>
                            </th>
                            <th scope="col" class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="!leads.data.length">
                            <td colspan="5" class="text-center">
                                Nenhum Lead encontrato para este passo!
                            </td>
                        </tr>
                        <tr v-else v-for="lead in leads.data" :key="lead.id">
                            <td :style="getColumnStyle('created_at')">{{ lead.created_at | formatDateTime }}</td>
                            <td scope="row" :style="getColumnStyle('transaction_code')">{{ lead.transaction_code }}</td>
                            <td :style="getColumnStyle('customer_name')">{{ lead.customer.customer_name }}</td>
                            <td :style="getColumnStyle('value')">{{ lead.value | currency }}</td>
                            <td :style="getColumnStyle('status')">{{ lead.lead_status.status }}</td>
                            <td class="text-center">
                                <lead-actions
                                    :lead="lead"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr class="mb-0 mt-0">
                <div class="mt-1">
                    <pagination :data="leads" :show-disabled="true" align="center" @pagination-change-page="getResults"></pagination>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>

import Pagination from 'laravel-vue-pagination'
import LeadActions from './LeadActions'

export default {
    data() {
        return {
            leads: {
                data: []
            },
            isLoading: true,
            searchString: '',
            orderBy: {
                column: 'leads.created_at',
                asc: true
            }
        }
    },
    props: {
        stepId: {
            type: String,
            required: true
        }
    },
    components: {
        Pagination,
        LeadActions
    },
    mounted() {
        this.getResults()
    },
    methods: {
        async setOrderBy(column) {
            if (this.orderBy.column === column) {
                this.orderBy.asc = !this.orderBy.asc
            } else {
                this.orderBy.column = column
                this.orderBy.asc = true
            }
            await this.getResults()
        },
        getOrderBy() {
            let type = this.orderBy.asc ? 'ASC' : 'DESC'
            return `&orderBy=${this.orderBy.column}&orderType=${type}`
        },
        getColumnStyle(column) {
            if (column === this.orderBy.column) {
                return {
                    'background-color': 'whitesmoke'
                }
            } else {
                return {}
            }
        },
        getOrderIcon(column) {
            return {
                'icon-code': this.orderBy.column != column,
                'icon-chevrons-down': this.orderBy.asc,
                'icon-chevrons-up': !this.orderBy.asc
            }
        },
        async getResults(page = 1) {
            let url = ''
            let orderBy = this.getOrderBy()
            if (this.searchString) {
                url = `leads/${this.stepId}?page=${page}${orderBy}&searchValue=${this.searchString}`
            } else {
                url = `leads/${this.stepId}?page=${page}${orderBy}`
            }
			await this.$http.get(url)
				            .then(res => {
                                this.leads = res.data
                                this.isLoading = false
                            })
        },
        getSchedules(lead) {
            return lead.schedules.filter(ls => ls.funnel_step_id === lead.funnel_step_id)
        }
    }
}
</script>
