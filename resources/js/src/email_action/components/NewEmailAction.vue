<template>
    <div class="card mb-0">
        <div class="card-header bg-primary text-white p-1">
            <i class="fas fa-envelope fa-2x"></i> Nova mensagem de E-mail
        </div>
        <div class="card-body pl-0 pr-0">
            <div class="row mb-1">
                <div class="col">
                    <label for="action_description">Descrição</label>
                    <input type="text" name="action_description" id="action_description" class="form-control" v-model="action_description" placeholder="Exemplo: Enviar SMS">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="email-message-editor">Texto do E-mail</label>
                    <quill-editor ref="emailMessageEditor" id="email-message-editor" v-model="action_data.data"
                        :options="{placeholder: 'Digite a mensagem a ser enviada...', theme: 'snow' }"></quill-editor>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <hr />
                    <fieldset>
                        <label>Enviar somente no horário entre:</label>
                        <div class="d-flex justify-content-between">
                            <div class="form-control mr-1" style="width: 7rem">{{ action_data.options.period[0] }} Horas</div>
                            <div class="flex-grow-1"><vs-slider step=1 :min=0 :max=23 text-fixed="horas" v-model="action_data.options.period"/></div>
                            <div class="form-control ml-1" style="width: 7rem">{{ action_data.options.period[1] }} Horas</div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <button class="btn btn-secondary float-right" @click="cancelNewEmailAction"><i class="fas fa-times"></i> Cancelar</button>
                    <button class="btn btn-success float-right mr-1" @click="saveEmailAction"><i class="fas fa-save"></i> Salvar mensagem</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { quillEditor } from 'vue-quill-editor'
import { mapState, mapActions, mapGetters } from 'vuex'
import * as componentTypes from '../../steps/components/component-types'

const iniData = {
    id: null,
    action_description: 'Enviar Email',
    action_sequence: 0,
    action_data: {
        data: '',
        options: {
            period: [0,23]
        }
    },
    action_type_id: null
}

export default {
    data() {
        return {
            ...iniData,
        }
    },
    components: {
        quillEditor
    },
    computed: {
        ...mapState('steps', [
            'isEditing', 'editingIndex', 'listActions'
        ]),
        ...mapGetters('variables', [
            'GetVariablesAsObject'
        ]),
        ...mapGetters('steps', [
            'GetActionByIndex'
        ]),
        ...mapGetters('funnel', [
            'GetActionTypeByName'
        ])
    },
    methods: {
        ...mapActions('steps', [
            'ActionSetActiveComponent', 'ActionSetNewAction', 'ActionSetUpdateAction'
        ]),
        saveEmailAction() {
            this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
            if (this.isEditing) {
                this.ActionSetUpdateAction(this.$data)
            } else {
                this.ActionSetNewAction(this.$data)
            }
        },
        cancelNewEmailAction() {
            this.$swal.fire({
                    title: 'Cancelar cadastro da ação?',
                    text: `Os dados informados serão perdidos...`,
                    icon: 'warning',
                    heightAuto: false,
                    showCancelButton: true,
                    confirmButtonText: 'Sim, Cancelar!',
                    cancelButtonText: 'Não, Continuar.'
                }).then(result => {
                    if (result.value) {
                        this.ActionSetActiveComponent(componentTypes.COMPONENT_TABLE)
                    }
                })
        },
        addCustomSelectToEditor() {
            const quill = this.$refs.emailMessageEditor.quill

            const variablesDW = new QuillToolbarDropDown({
                label: "Variáveis",
                rememberSelection: false
            })

            variablesDW.setItems(this.GetVariablesAsObject)

            variablesDW.onSelect = function(label, value, quill) {
                const { index, length } = quill.selection.savedRange
                quill.deleteText(index, length)
                quill.insertText(index, value)
                quill.setSelection(index + value.length)
            }

            variablesDW.attach(quill)
        }
    },
    mounted() {
        this.addCustomSelectToEditor()
        if (this.isEditing) {
            Object.assign(this.$data, { ...this.GetActionByIndex(this.editingIndex) })
        } else {
            this.action_type_id = this.GetActionTypeByName('email').id
            this.action_sequence = (this.listActions.length ?? 0) + 1
        }
    }
}
</script>
