export const GetUnselectedColumns = state => {
    return state.columns.filter(c => !state.columnsSelected.find(cs => cs.id === c.id))
}

export const GetCustomerStatusesForSelect = state => {
    let res = []
    state.statuses.forEach(customerStatus => {
        res.push({
            id: customerStatus.id,
            text: customerStatus.customer_status
        })
    })
    return res
}

export const GetCustomersForImport = state => {
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

    if (state.firstLineCaption) {
        return state.customers.slice(1).sort((a, b) => compare(a[state.order.field]['content'], b[state.order.field]['content'], state.order.type))
    } else {
        return state.customers.slice().sort((a, b) => compare(a[state.order.field]['content'], b[state.order.field]['content'], state.order.type))
    }
}


// export const GetCustomersForPost = (state, getters) => {
//     let result = []
//     getters.GetCustomersForImport.forEach(line => {
//         console.log(line.find(f => f['column'] === 'customer_name'))
//         result.push({
//             'customer_name': line.find(f => f['column'] === 'customer_name')['content'],
//             'customer_email': line.find(f => f['column'] === 'customer_email')['content'],
//             'customer_phone_numbner': line.find(f => f['column'] === 'customer_phone_numbner')['content']
//         })
//     })
//     return result
// }
// export const GetCustomersForPost = (state, getters) => {
//     let result = []
//     getters.GetCustomersForImport.forEach(line => {

//         line.forEach(field => {
//             console.log(field)
//             if (field) {
//                 result.push({
//                     'customer_name': field.find(f => f === 'customer_name')['content'],
//                     'customer_email': field.find(f => f === 'customer_email')['content'],
//                     'customer_phone_numbner': field.find(f => f === 'customer_phone_numbner')['content']
//                 })
//             }
//         })
//     })
//     return result
// }
