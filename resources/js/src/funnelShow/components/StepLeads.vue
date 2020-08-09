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
                                value=""
                                placeholder="Buscar por..."
                            />
                            <div class="input-group-append">
                                <button class="btn btn-primary icon-btn-sm-padding">
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
                    <thead class="bg-light text-dark">
                        <tr>
                            <th scope="col">Transação</th>
                            <th scope="col">Data da Compra</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Valor</th>
                            <th scope="col">Status</th>
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
                            <td scope="row">{{ lead.transaction_code }}</td>
                            <td>{{ lead.created_at | formatDateTime }}</td>
                            <td>{{ lead.customer.customer_name }}</td>
                            <td>{{ lead.value | currency }}</td>
                            <td>{{ lead.lead_status.status }}</td>
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
            isLoading: true
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
        async getResults(page = 1) {
			await this.$http.get(`leads/${this.stepId}?page=${page}`)
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
