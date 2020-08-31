import * as componentTypes from '../../action/component-types'

export const setupState = () => {
    return {
        id: null,
        actions: [],
        funnel_step_sequence: null,
        actionComponent: componentTypes.ACTIONS_TABLE,
        actionEditingIndex: null,
        deleted: false,
        postbackEventTypeId: null,
    }
}

export default setupState()
