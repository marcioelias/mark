export default {
    httpErrors: [],
    isLoading: false,
    importFile: null,
    customers: [],
    statuses: [],
    selectedState: null,
    columns: [
        {
            name: 'customer_name',
            label: 'Nome'
        },
        {
            name: 'customer_email',
            label: 'E-mail'
        },
        {
            name: 'customer_phone_number',
            label: 'Telefone'
        }
    ],
    selectedColumns: [],
    firstLineCaption: false,
    order: {
        field: 0,
        type: 'ASC',
    },
}
