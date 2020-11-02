export const GetProductsForSelect = state => {
    let res = []
    state.products.forEach(product => {
        res.push({
            id: product.id,
            text: product.product_name
        })
    })
    return res
}

export const GetCustomerStatusesForSelect = state => {
    let res = []
    state.customerStatuses.forEach(status => {
        res.push({
            id: status.id,
            text: status.customer_status
        })
    })

    return res
}

export const GetPaymentTypesForSelect = state => {
    let res = []
    state.paymentTypes.forEach(paymentType => {
        res.push({
            id: paymentType.id,
            text: paymentType.payment_type
        })
    })

    return res
}

export const GetOrderedCustomers = state => {
    function compare(a, b, c) {
        if (c === 'ASC') {
            if (typeof (a) == 'string') {
                return a.localeCompare(b)
            } else {
                return a - b
            }
        } else {
            if (typeof (a) == 'string') {
                return b.localeCompare(a)
            } else {
                return b - a
            }
        }
    }
    return state.customers.slice().sort((a, b) => compare(a[state.order.field], b[state.order.field], state.order.type))
}
