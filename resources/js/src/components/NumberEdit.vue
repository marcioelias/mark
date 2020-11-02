<template>
    <div class="d-block">
       <label :for="id"><slot></slot></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <button class="btn btn-sm btn-secondary pl-1 pr-1" @click="sub"><i class="fas fa-minus"></i></button>
            </div>
            <input type="number" :name="name" :id="id" class="form-control" :value="value" @input="$emit('input', $event.target.value*1)">
            <div class="input-group-append">
                <button class="btn btn-sm btn-secondary pl-1 pr-1" @click="add"><i class="fas fa-plus"></i></button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        min: {
            type: Number,
            default: 0
        },
        max: {
            type: Number,
            defult: 999
        },
        name: {
            type: String,
            required: true
        },
        id: {
            type: String,
            required: true
        },
        value: {
            type: Number,
            default: 0
        }
    },
    methods: {
        add() {
            if (this.value < this.max) {
                this.$emit('input', this.value+1)
            }
        },
        sub() {
            if (this.value > this.min) {
                this.$emit('input', this.value-1)
            }
        }
    }
}
</script>
