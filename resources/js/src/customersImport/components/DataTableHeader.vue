<template>
    <th :class="{ 'bg-warning': ordered, 'bg-primary': !ordered }">
        <span class="d-flex justify-content-between align-items-center">
            <span class="text-white d-flex align-items-center w-100"><slot /></span>
            <div class="btn btn-table-order"
                :class="{ 'btn-warning': ordered, 'btn-primary': !ordered }"
                data-toggle="tooltip"
                data-placement="top"
                title="Ordernar"
                @click.prevent="setOrder">
                <i class="feather" :class="getIcon()"></i>
            </div>
        </span>
    </th>
</template>

<script>
import { mapActions, mapState } from 'vuex'
export default {
    props: {
        field: {
            type: Number,
            required: true
        }
    },
    computed: {
        ...mapState('customersImport', [
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
        ...mapActions('customersImport', [
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
