<template>
    <div class="dropdown">
        <button class="btn btn-success  dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-plus"></i> <slot></slot>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a v-for="actionType in actionTypes" :key="actionType.id" class="dropdown-item" @click.prevent="$emit('on-add-action', actionType.id)"><i :class="getActionIcon(actionType.id)"></i> {{ actionType.action_type_description }}</a>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import { ACTION } from '../../../user/constants'

export default {
    mounted() {
        this.ActionSetActionTypes({ vm: this })
    },
    computed: {
        ...mapState('actionButton', [
            'actionTypes',
            'isLoading'
        ])
    },
    methods: {
        ...mapActions('actionButton', [
            'ActionSetActionTypes'
        ]),
        getActionIcon(actionTypeId) {
            switch (actionTypeId) {
                case ACTION.WHATSAPP:
                    return {'fab': true, 'fa-whatsapp': true}
                    break;
                case ACTION.SMS:
                    return {'fa': true, 'fa-sms': true}
                    break;
                case ACTION.EMAIL:
                    return {'fa': true, 'fa-envelope': true}
                    break;
                case ACTION.FUNNEL:
                    return {'fas': true, 'fa-filter': true}
                    break;
            }
        }
    }
}
</script>
