<template>
    <th :class="{ 'bg-warning': ordered, 'bg-primary': !ordered }">
        <span class="d-flex justify-content-between align-items-center">
            <span class="text-white d-flex align-items-center"><slot />{{ caption }}</span>
            <div class="btn btn-table-order"
                :class="{ 'btn-warning': ordered, 'btn-primary': !ordered }"
                data-toggle="tooltip"
                data-placement="top"
                :title="'Ordernar por '+caption"
                @click.prevent="setOrder"
                v-if="field != ''">
                <i class="feather" :class="getIcon()"></i>
            </div>
        </span>
    </th>
</template>

<script>
import { mapActions, mapState } from 'vuex'
export default {
    props: {
        caption: {
            type: String,
            required: true
        },
        field: {
            type: String,
            default: ''
        }
    },
    computed: {
        ...mapState('marketingAction', [
            'order'
        ]),
        ordered() {
            return this.order.field === this.field
        },
        orderType: {
            get() {
                return this.order.type
            },
            set(value) {
                this.ActionSetOrderType(value)
            }
        }
    },
    methods: {
        ...mapActions('marketingAction', [
            'ActionSetOrderField',
            'ActionSetOrderType'
        ]),
        setOrder() {
            if (this.ordered) {
                this.orderType = this.orderType === 'ASC' ? 'DESC' : 'ASC'
            } else {
                this.orderType = 'ASC'
                this.ActionSetOrderField(this.field)
            }
        },
        getIcon() {
            if (this.ordered) {
                if (this.orderType === 'ASC') {
                   return { 'icon-chevrons-down': true }
                } else {
                    return { 'icon-chevrons-up': true }
                }
            } else {
                return { 'icon-code': true }
            }
        }
    }
}
</script>
