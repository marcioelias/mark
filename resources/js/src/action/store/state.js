export const setupActionState = () => {
    return {
        id: null,
        action_type_id: null,
        action_sequence: 0,
        action_description: null,
        action_data: {
            data: '',
            options: {}
        },
        deleted: false
    }
}

export default {
    ...setupActionState()
}
