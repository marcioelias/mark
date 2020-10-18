<template>
    <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuVariables" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-plus"></i> Incluir Variável
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuVariables">
            <a class="dropdown-item" v-for="variable in GetVariables" :key="variable.id" @click="insertVariable(variable.variable)">{{ variable.description }}</a>
        </div>
    </div>
</template>

<script>
import insertTextAtCursor from 'insert-text-at-cursor'
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        component: {
            type: String,
            required: true
        }
    },
    computed: {
        ...mapGetters('variables', [
            'GetVariables'
        ])
    },
    methods: {
        ...mapActions('variables', [
            'ActionGetVariablesFromApi'
        ]),
        insertVariable(value) {
            insertTextAtCursor(this.$parent.$refs[this.component], value)
        }
    },
    mounted() {
        this.ActionGetVariablesFromApi({ vm: this })
    }
}
</script>
