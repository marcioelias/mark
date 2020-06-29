<template>
    <div>
        <div class="card mb-1">
            <!-- <div class="card-header bg-primary text-white p-1">
                Configurações
            </div> -->
            <div class="card-body pl-0 pr-0 pt-0">
                <div class="row">
                    <div class="col-md-3">
                        <label for="delay_days">Executar Após Dias</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" @click="delayDays > 0 ? delayDays-- : 0"><i class="fas fa-minus"></i></button>
                            </div>
                            <input type="number" name="dalay_days" id="dalay_days" class="form-control" v-model="delayDays">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="delayDays < 30 ? delayDays++ : 30"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="delay_hours">Horas</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <button class="btn btn-secondary" @click="delayHours > 0 ? delayHours-- : 0"><i class="fas fa-minus"></i></button>
                            </div>
                            <input type="number" name="dalay_hours" id="dalay_hours" class="form-control" max="23" v-model="delayHours">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" @click="delayHours < 23 ? delayHours++ : 23"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="new_tag">Nova Tag</label>
                        <Select2 v-model="newTag" name="new_tag" id="new_tag" :options="GetNewTagsForSelect" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-primary text-white p-1 d-flex justify-content-between align-items-center" v-if="activeComponent == 'StepActionsTable'">
                <div>Ações</div>
                <div class="dropdown">
                    <button class="btn btn-success  dropdown-toggle icon-btn-sm-padding" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus"></i> Nova ação
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" @click.prevent="newEmailAction"><i class="fas fa-envelope"></i> Enviar E-mail</a>
                        <a class="dropdown-item" @click.prevent="newSmsAction"><i class="fas fa-sms"></i> Enviar SMS</a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <component :is="activeComponent"></component>
            </div>
            <div class="card-footer" v-if="activeComponent == 'StepActionsTable'">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-success float-right" @click="saveStep"><i class="fas fa-save"></i> Salvar passo</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Select2 from 'v-select2-component';
import { mapState, mapGetters, mapActions } from 'vuex'
import StepActionsTable from './StepActionsTable'
import NewSmsAction from '../../sms_action/components/NewSmsAction'
import NewEmailAction from '../../email_action/components/NewEmailAction'
import * as componentTypes from './component-types'

export default {
    data() {
        return {
            tags: ['tag 1', 'tag 2', 'tag 3', 'tag 4', 'tag 5'],
            data: [
                {
                    id: 1,
                    name: 'Enviar SMS'
                },
                {
                    id: 2,
                    name: 'Enviar E-mail'
                }
            ],
            dias: 0,
            horas: 0,
            delayDays: 0,
            delayHours: 0,
            actionIndex: null
        }
    },
    components: {
        Select2, StepActionsTable, NewSmsAction, NewEmailAction
    },
    computed: {
        ...mapState('steps', [
            'activeComponent', 'listActions'
        ]),
        ...mapState('funnel', [
            'steps'
        ]),
        ...mapGetters('funnel', [
            'GetTagsForSelect', 'GetNewTagsForSelect'
        ]),
        originalTag: {
            get() {
                return this.$store.state.funnel.originalTag
            },
            set(value) {
                this.$store.dispatch('funnel/ActionSetOriginalTag', value)
            }
        },
        newTag: {
            get() {
                return this.$store.state.funnel.newTag
            },
            set(value) {
                this.$store.dispatch('funnel/ActionSetNewTag', value)
            }
        }
    },
    mounted() {
        this.$store.dispatch('funnel/ActionGetTags', { vm: this })
        this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
    },
    methods: {
        ...mapActions('steps', [
            'ActionSetActiveComponent'
        ]),
        ...mapActions('funnel', [
            'ActionSetShowCrudStep', 'ActionAddNewStep'
        ]),
        newEmailAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_NEW_EMAIL)
        },
        newSmsAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_NEW_SMS)
        },
        doOnEditAction(index) {
            let act = this.listActions[index]
        },
        saveStep() {
            this.ActionAddNewStep({
                id: 321,
                name: `Passo ${this.steps.length + 1}`,
                data: { ...this.listActions }
            })
            this.ActionSetShowCrudStep(false)
        }
    }
}
</script>
