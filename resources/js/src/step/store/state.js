import * as componentTypes from '../../action/component-types'

export const setupState = () => {
    return {
        id: null,
        funnel_step_sequence: 0,
        funnel_step_description: '',
        new_tag_id: null,
        delay_days: 0,
        delay_hours: 0,
        actions: [],
        actionComponent: componentTypes.ACTIONS_TABLE,
        actionEditingIndex: null,
        deleted: false
    }
}

export default setupState()
