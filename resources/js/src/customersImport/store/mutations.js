import * as types from './mutation-types'

export default {
    [types.SET_HTTP_ERRORS] (state, payload) {
        state.httpErrors = payload
    },
    [types.SET_IS_LOADING] (state, payload) {
        state.isLoading = payload
    },
    [types.SET_CUSTOMERS] (state, payload) {
        state.customers = payload
    },
    [types.SET_STATUSES] (state, payload) {
        state.statuses = payload
    },
    [types.SET_STATUS_SELECTED] (state, payload) {
        state.selectedState = payload
    },
    [types.SET_SELECTED_COLUMNS] (state, payload) {
        state.selectedColumns.push(payload)
    },
    [types.SET_IMPORT_FILE] (state, payload) {
        state.importFile = payload
    },
    [types.SET_FIRST_LINE_CAPTION] (state, payload) {
        state.firstLineCaption = payload
    },
    [types.SET_COLUMN_OF_DATA] (state, payload) {
        state.customers.forEach(customer => customer.forEach(field => {
            if (field.index === payload.index) {
                field.column = payload.column
            }
        }))
    },
    [types.SET_ORDER_FIELD] (state, payload) {
        state.order.field = payload
    },
    [types.SET_ORDER_TYPE] (state, payload) {
        state.order.type = payload
    },
    [types.SET_SEPARATOR] (state, payload) {
        state.separator = payload
    }
}
