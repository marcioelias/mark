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

export const GetTagsForSelect = state => {
    let res = []
    state.tags.forEach(tag => {
        res.push({
            id: tag.id,
            text: tag.tag_name
        })
    })
    return res
}

export const GetTagById = state => id => {
    return state.tags.find(tag => tag.id === id)
}

export const GetNewTagsForSelect = (state, getters) => {
    return getters.GetTagsForSelect.filter(tag => tag.id != state.originalTag)
}

export const GetStepByIndex = state => index => {
    return state.steps[index]
}

export const GetActionTypeByName = state => name => {
    return state.actionTypes.find(act => act.action_type_name === name)
}

export const GetActionTypeById = state => id => {
    return state.actiontypes.find(act => act.id === id)
}

export const OrderedSteps = state => {
    return state.steps.sort((a, b) => a.funnel_step_sequence - b.funnel_step_sequence)
}
