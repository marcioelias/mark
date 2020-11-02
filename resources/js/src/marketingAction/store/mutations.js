import * as types from './mutation-types'

export default {
    [types.SET_HTTP_ERRORS] (state, payload) {
        state.httpErrors = payload
    },
    [types.SET_IS_LOADING] (state, payload) {
        state.isLoading = payload
    },
    [types.SET_DESCRIPTION] (state, payload) {
        state.description = payload
    },
    [types.SET_PRODUCT_ID] (state, payload) {
        state.product_id = payload
    },
    [types.SET_START_DATE] (state, payload) {
        state.startDate = payload
    },
    [types.SET_START_TIME] (state, payload) {
        state.startTime = payload
    },
    [types.SET_PRODUCTS] (state, payload) {
        state.products = payload
    },
    [types.SET_CUSTOMERS] (state, payload) {
        state.customers = payload
    },
    [types.SET_IS_EDITING_CUSTOMERS] (state, payload) {
        state.isEditingCustomers = payload
    },
    [types.SET_MESSAGE_TYPE] (state, payload) {
        state.messageType = payload
    },
    [types.SET_MESSAGE] (state, payload) {
        state.message.data = payload
    },
    [types.SET_FILTER_CUSTOMER_STATUS] (state, payload) {
        state.filters.customerStatus = payload
    },
    [types.SET_FILTER_DT_LAST_LEAD_BEGIN] (state, payload) {
        state.filters.dtLastLeadBegin = payload
    },
    [types.SET_FILTER_DT_LAST_LEAD_END] (state, payload) {
        state.filters.dtLastLeadEnd = payload
    },
    [types.SET_FILTER_PRODUCT_ID] (state, payload) {
        state.filters.productId = payload
    },
    [types.SET_FILTER_PAYMENT_TYPE_ID] (state, payload) {
        state.filters.paymentTypeId = payload
    },
    [types.SET_CUSTOMER_STATUSES] (state, payload) {
        state.customerStatuses = payload
    },
    [types.SET_PAYMENT_TYPES] (state, payload) {
        state.paymentTypes = payload
    },
    [types.SET_ORDER_FIELD] (state, payload) {
        state.order.field = payload
    },
    [types.SET_ORDER_TYPE] (state, payload) {
        state.order.type = payload
    },
    [types.TOGGLE_CUSTOMER_CHECK] (state, payload) {
        state.customers.forEach(customer => customer.checked = payload)
    },
    [types.ADD_EMAIL_MESSAGE_IMAGE] (state, payload) {
        state.message.options.images.push(payload)
    },
    [types.SET_EMAIL_SUBJECT] (state, payload) {
        state.message.options.subject = payload
    },
    [types.RESET_MESSAGE] (state) {
        state.message = {
            data: '',
            options: {
                subject: '',
                images: []
            }
        }
    },
    [types.SET_WHATSAPP_ENABLE] (state, payload) {
        state.whatsappEnable = payload
    }
}
