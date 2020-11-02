import * as types from './mutation-types'

export const ActionSetHttpErrors = ({ commit }, payload) => {
    commit(types.SET_HTTP_ERRORS, payload)
}

export const ActionSetIsLoading = ({ commit }, payload) => {
    commit(types.SET_IS_LOADING, payload)
}

export const ActionSetDescription = ({ commit }, payload) => {
    commit(types.SET_DESCRIPTION, payload)
}

export const ActionSetProductId = ({ state, commit, dispatch }, payload) => {
    commit(types.SET_PRODUCT_ID, payload)
    if (payload) {
        let prod = state.products.find(p => p.id == payload)
        commit(types.SET_WHATSAPP_ENABLE, prod.whatsapp_instance)
        if (!prod.whatsapp_instance && state.messageType == '1ef0d03c-9f90-408a-b14b-ccee49a2dd6d') {
            dispatch('ActionCancelMessage')
        }
    } else {
        commit(types.SET_WHATSAPP_ENABLE, false)
    }
}

export const ActionSetStartDate = ({ commit }, payload) => {
    commit(types.SET_START_DATE, payload)
}

export const ActionSetStartTime = ({ commit }, payload) => {
    commit(types.SET_START_TIME, payload)
}

export const ActionSetCustomers = ({ commit }, payload) => {
    commit(types.SET_CUSTOMERS, payload)
}

export const ActionSetIsEditingCustomers = ({ commit }, payload) => {
    commit(types.SET_IS_EDITING_CUSTOMERS, payload)
}

export const ActionSetWhatsappEnable = ({ commit }, payload) => {
    commit(types.SET_WHATSAPP_ENABLE, payload)
}

export const ActionSetMessageType = ({ commit }, payload) => {
    commit(types.SET_MESSAGE_TYPE, payload)
}

export const ActionSetMessage = ({ commit }, payload) => {
    commit(types.SET_MESSAGE, payload)
}

export const ActionAddEmailMessageImage = ({ commit }, payload) => {
    commit(types.ADD_EMAIL_MESSAGE_IMAGE, payload)
}

export const ActionSetEmailSubject = ({ commit }, payload) => {
    commit(types.SET_EMAIL_SUBJECT, payload)
}

export const ActionCancelMessage = ({ commit }) => {
    commit(types.SET_MESSAGE_TYPE, null)
    commit(types.RESET_MESSAGE)
}

export const ActionSetFilterCustomerStatus = ({ commit }, payload) => {
    commit(types.SET_FILTER_CUSTOMER_STATUS, payload)
}

export const ActionSetFilterDtLastLeadBegin = ({ commit }, payload) => {
    commit(types.SET_FILTER_DT_LAST_LEAD_BEGIN, payload)
}

export const ActionSetFilterDtLastLeadEnd = ({ commit }, payload) => {
    commit(types.SET_FILTER_DT_LAST_LEAD_END, payload)
}

export const ActionSetFilterProductId = ({ commit }, payload) => {
    commit(types.SET_FILTER_PRODUCT_ID, payload)
}

export const ActionSetFilterPaymentTypeId = ({ commit }, payload) => {
    commit(types.SET_FILTER_PAYMENT_TYPE_ID, payload)
}

export const ActionSetOrderType = ({ commit }, payload) => {
    commit(types.SET_ORDER_TYPE, payload)
}

export const ActionSetOrderField = ({ commit }, payload) => {
    commit(types.SET_ORDER_FIELD, payload)
}

export const ActionToggleCustomerSelect = ({ commit }, payload) => {
    commit(types.TOGGLE_CUSTOMER_CHECK, payload)
}

export const ActionGetProducts = async ({ commit }, { vm }) => {
    await vm.$http.get('products/json')
                  .then(res => commit(types.SET_PRODUCTS, res.data || []))
                  .catch(err => console.log(err))
}

export const ActionGetCustomerStatuses = async ({ commit }, { vm }) => {
    await vm.$http.get('customer/statuses/json')
                  .then(res => commit(types.SET_CUSTOMER_STATUSES, res.data))
                  .catch(err => console.log(err))
}

export const ActionGetPaymentTypes = async ({ commit }, { vm }) => {
    await vm.$http.get('payment_types/json')
                  .then(res => commit(types.SET_PAYMENT_TYPES, res.data))
                  .catch(err => console.log(err))
}

export const ActionSearchCustomers = async ({ state, commit }, { vm }) => {
    await vm.$http.post('customers/json', { ...state.filters })
            .then(res => commit(types.SET_CUSTOMERS, res.data))
            .catch(err => console.log(err))
}

export const ActionSaveMarketingAction = ({ state, commit }, { vm }) => {
    return vm.$http.post('marketing_action', {
                description: state.description,
                product_id: state.product_id,
                action_type_id: state.messageType,
                message: { ...state.message },
                start_date: state.startDate,
                start_time: state.startTime,
                customers: state.customers.filter(customer => customer.checked).map(c => c.id)
            })
}
