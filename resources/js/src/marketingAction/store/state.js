export default {
    httpErrors: [],
    isLoading: false,
    description: '',
    product_id: null,
    startDate: null,
    startTime: null,
    products: [],
    customers: [],
    customerStatuses: [],
    paymentTypes: [],
    filters: {
        customerStatus: null,
        dtLastLeadBegin: null,
        dtLastLeadEnd: null,
        productId: null,
        paymentTypeId: null
    },
    order: {
        field: 'customer_name',
        type: 'ASC',
    },
    messageType: null,
    message: {
        data: '',
        options: {
            subject: '',
            images: []
        }
    },
    isEditingCustomers: true,
    whatsappEnable: false
}
