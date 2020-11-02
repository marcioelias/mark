<template>
    <div>
        <div class="row">
            <div class="col-lg-6 col-md-6">
                <fieldset class="form-group">
                    <label for="basicInputFile">Arquivo CSV</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" :class="{ 'is-invalid': httpErrors.hasOwnProperty('customers') }" id="file-upload" ref="file" @change="handleFileUpload()">
                        <label class="custom-file-label" for="file-upload">Carregar</label>
                    </div>
                    <span v-show="httpErrors.hasOwnProperty('customers')" class="invalid-feedback" style="display: block">
                        <span v-for="(error, index) in httpErrors.customers" :key="index">{{ error }}</span>
                    </span>
                </fieldset>
            </div>
            <div class="col-lg-3 col-md-3">
                <label for="customer_status">Status</label>
                <Select2 v-model="currentStatusId" class="w-100" :class="{ 'is-invalid': httpErrors.hasOwnProperty('customer_status_id') }" name="customer_status" id="customer_status"
                        :options="GetCustomerStatusesForSelect" />
                <span v-show="httpErrors.hasOwnProperty('customer_status_id')" class="invalid-feedback" style="display: block">
                    <span v-for="(error, index) in httpErrors.customer_status_id" :key="index">{{ error }}</span>
                </span>
            </div>
            <div class="col-lg-3 col-md-3 d-flex align-items-center">
                <button class="btn btn-primary btn-block" @click="loadFile" :disabled="!importFile">
                    <template v-if="isLoading">
                        <i class="fas fa-circle-notch fa-spin"></i>
                        Carregando...
                    </template>
                    <template v-else>
                        <i class="fas fa-file-import mr-1"></i>
                        Carregar arquivo
                    </template>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <fieldset>
                    <div class="vs-checkbox-con vs-checkbox-primary">
                        <input type="checkbox" class="mr-1" v-model="currentFirstLineCaption">
                        <span class="vs-checkbox vs-checkbox-sm">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span>
                        <span class="">Primeira linha contém o Título das colunas?</span>
                    </div>
                </fieldset>
            </div>
        </div>
        <data-table></data-table>
        <form-footer></form-footer>
    </div>
</template>

<script>
import Select2 from 'v-select2-component'
import { mapActions, mapGetters, mapState } from 'vuex'
import DataTable from './DataTable'
import FormFooter from './FormFooter'

export default {
    components: {
        Select2, DataTable, FormFooter
    },
    computed: {
        ...mapGetters('customersImport', [
            'GetCustomerStatusesForSelect'
        ]),
        ...mapState('customersImport', [
            'selectedState',
            'firstLineCaption',
            'isLoading',
            'importFile',
            'httpErrors'
        ]),
        currentStatusId: {
            get() {
                return this.selectedState
            },
            set(value) {
                this.ActionSetSelectedState(value)
            }
        },
        currentFirstLineCaption: {
            get() {
                return this.firstLineCaption
            },
            set(value) {
                this.ActionSetFirstLineCaption(value)
            }
        }
    },
    methods: {
        ...mapActions('customersImport', [
            'ActionSetSelectedState',
            'ActionSetStatuses',
            'ActionSetImportFile',
            'ActionFileUpload',
            'ActionSetFirstLineCaption'
        ]),
        loadFile() {
            this.ActionFileUpload({ vm: this })
        },
        handleFileUpload() {
            if (this.$refs.file) {
                this.ActionSetImportFile(this.$refs.file.files[0])
            }
        }
    },
    mounted() {
        this.ActionSetStatuses({ vm: this })
    }
}
</script>
