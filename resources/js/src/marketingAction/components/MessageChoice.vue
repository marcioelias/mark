<template>
    <div class="row">
        <div class="col-4">
            <button class="btn btn-block btn-lg"
                    :class="{ 'btn-outline-success': !httpErrors.hasOwnProperty('action_type_id'), 'btn-outline-danger': httpErrors.hasOwnProperty('action_type_id') }"
                    @click="ActionSetMessageType(ACTION.SMS)">
                <i class="fa fa-sms"></i> SMS
            </button>
        </div>
        <div class="col-4">
            <button class="btn btn-block btn-lg"
                    :class="{ 'btn-outline-success': !httpErrors.hasOwnProperty('action_type_id'), 'btn-outline-danger': httpErrors.hasOwnProperty('action_type_id') }"
                    @click="ActionSetMessageType(ACTION.EMAIL)">
                <i class="fa fa-envelope"></i> E-mail
            </button>
        </div>
        <div class="col-4">
            <button class="btn btn-block btn-lg"
                    :class="{ 'btn-outline-success': !httpErrors.hasOwnProperty('action_type_id') && whatsappEnable, 'btn-outline-danger': httpErrors.hasOwnProperty('action_type_id') && whatsappEnable, 'btn-outline-light': !whatsappEnable }"
                    @click="ActionSetMessageType(ACTION.WHATSAPP)"
                    :disabled="!whatsappEnable">
                <i class="fab fa-whatsapp"></i> Whatsapp
            </button>
        </div>
        <span v-show="httpErrors.hasOwnProperty('action_type_id')" class="invalid-feedback ml-1" style="display: block">
            <span v-for="(error, index) in httpErrors.action_type_id" :key="index">{{ error }}</span>
        </span>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import { ACTION } from '../../../user/constants'
export default {
    data() {
        return {
            ACTION
        }
    },
    computed: {
        ...mapState('marketingAction', [
            'httpErrors',
            'whatsappEnable'
        ])
    },
    methods: {
        ...mapActions('marketingAction', [
            'ActionSetMessageType'
        ])
    }
}
</script>
