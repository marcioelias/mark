<template>
	<div class="card mb-0">
		<div class="card-header bg-primary text-white p-1">
			<i class="fas fa-envelope fa-2x"></i> Nova mensagem de E-mail
		</div>
		<div class="card-body pl-0 pr-0">
			<div class="row mb-1">
				<div class="col col-lg-4">
					<label for="action_description">Descrição</label>
					<input
						type="text"
						name="action_description"
						id="action_description"
						class="form-control"
						v-model="actionDescription"
						placeholder="Exemplo: Enviar E-mail"
					/>
				</div>
				<template v-if="options.extra.envio_imediato">
                    <div class="col col-lg-4 d-flex">
                        <div class="btn-group" role="group" aria-label="Agendamento Disparo">
                            <button type="button" class="btn" @click="options.extra.envio_imediato = true"
                            :class="{'btn-primary': options.extra.envio_imediato == null, 'btn-warning': options.extra.envio_imediato}">Envio imediato</button>
                            <button type="button" @click="options.extra.envio_imediato = false" class="btn btn-primary">Envio agendado</button>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="col col-lg-2">
                        <NumberEdit name="days_after" id="days_after" :min="0" :max="30" v-model="options.days_after">Enviar após dias</NumberEdit>
                    </div>
                    <div class="col col-lg-2">
                        <NumberEdit name="delay_minutes" id="delay_minutes" :min="0" :max="15" v-model="options.delay_minutes">Atraso/Minutos</NumberEdit>
                    </div>
                </template>

                <template v-if="options.extra.qualquer_horario">
                    <div class="col col-lg-4 d-flex">
                        <div class="btn-group" role="group" aria-label="Horário de disparo">
                            <button type="button" class="btn" @click="options.extra.qualquer_horario = true"
                            :class="{'btn-primary': options.extra.qualquer_horario == null, 'btn-warning': options.extra.qualquer_horario}">Qualquer horário</button>
                            <button type="button" class="btn btn-primary" @click="options.extra.qualquer_horario = false">Horário limitado</button>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <div class="col col-lg-4">
                        <label>Somente no período entre:</label>
                        <div class="d-flex justify-content-between align-items-baseline">
                            <flat-pickr
                                v-model="options.start_time"
                                class="form-control"
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                    time_24hr: true
                                }"
                            ></flat-pickr>
                            <span class="pl-1 pr-1"> e </span>
                            <flat-pickr
                                v-model="options.end_time"
                                class="form-control"
                                :config="{
                                    enableTime: true,
                                    noCalendar: true,
                                    dateFormat: 'H:i',
                                    minTime: options.start_time,
                                    time_24hr: true
                                }"
                            ></flat-pickr>
                        </div>
                        <!-- <NumberEdit name="delay_hours" id="delay_hours" :min="0" :max="23" @on-change="doOnChangeDelayHours">horas</NumberEdit> -->
                    </div>
                </template>
                    <!-- <NumberEdit name="delay_hours" id="delay_hours" :min="0" :max="23" @on-change="doOnChangeDelayHours">horas</NumberEdit> -->
			</div>
			<div class="row mb-1">
				<div class="col d-flex flex-row justify-content-between">
					<div class="flex-grow-1">
						<label for="mail_subject">Assunto</label>
						<div class="input-group">
							<input
								ref="emailSubject"
								type="text"
								name="mail_subject"
								id="mail_subject"
								class="form-control"
								v-model="options.subject"
								placeholder="Assunto do E-mail"
							/>
							<div class="input-group-append">
								<button
									class="btn btn-success dropdown-toggle"
									type="button"
									data-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false"
								>Variáveis</button>
								<div class="dropdown-menu">
									<a
										class="dropdown-item"
										v-for="variable in GetVariables"
										:key="variable.id"
										@click="insertVariable(variable.variable)"
									>{{ variable.description }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-end ml-2">
						<mail-template :message="data" :images="options.images" @templateLoaded="setDataFromTemplate" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<div class="custom-file d-none">
						<input
							ref="imageUpload"
							@change="imageUpload($event)"
							type="file"
							class="custom-file-input"
							id="imageUpload"
							aria-describedby="imageUploadAddon"
						/>
						<label class="custom-file-label" for="imageUpload">Choose file</label>
					</div>
					<label for="email-message-editor">Texto do E-mail</label>
					<quill-editor
						ref="emailMessageEditor"
						id="email-message-editor"
						v-model="data"
						rows="10"
						:options="editorOptions"
						@blur="onEditorBlur($event)"
						@focus="onEditorFocus($event)"
						@ready="onEditorReady($event)"
					></quill-editor>
				</div>
			</div>
		</div>
		<div class="card-footer">
			<div class="row">
				<div class="col">
					<button class="btn btn-secondary float-right" @click="cancelNewEmailAction">
						<i class="fas fa-times"></i> Cancelar
					</button>
					<button class="btn btn-success float-right mr-1" @click="saveEmailAction">
						<i class="fas fa-save"></i> Salvar mensagem
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<style>
.ql-picker-item[data-value="10px"]::before,
.ql-picker-label[data-value="10px"]::before {
	content: "10px" !important;
}
.ql-picker-item[data-value="12px"]::before,
.ql-picker-label[data-value="12px"]::before {
	content: "12px" !important;
}
.ql-picker-item[data-value="14px"]::before,
.ql-picker-label[data-value="14px"]::before {
	content: "14px" !important;
}
.ql-picker-item[data-value="16px"]::before,
.ql-picker-label[data-value="16px"]::before {
	content: "16px" !important;
}
.ql-picker-item[data-value="18px"]::before,
.ql-picker-label[data-value="18px"]::before {
	content: "18px" !important;
}
.ql-picker-item[data-value="20px"]::before,
.ql-picker-label[data-value="20px"]::before {
	content: "20px" !important;
}
.ql-picker-item[data-value="24px"]::before,
.ql-picker-label[data-value="24px"]::before {
	content: "24px" !important;
}
.ql-picker-item[data-value="30px"]::before,
.ql-picker-label[data-value="30px"]::before {
	content: "30px" !important;
}
.ql-picker-item[data-value="32px"]::before,
.ql-picker-label[data-value="32px"]::before {
	content: "32px" !important;
}
.ql-picker-item[data-value="36px"]::before,
.ql-picker-label[data-value="36px"]::before {
	content: "36px" !important;
}
</style>

<script>
import { quillEditor } from "vue-quill-editor";
import { mapState, mapActions, mapGetters } from "vuex";
import NumberEdit from '../../components/NumberEdit'
import flatPickr from 'vue-flatpickr-component'
import * as componentTypes from '../component-types'

import insertTextAtCursor from "insert-text-at-cursor";
import Quill from "quill";
import ImageResize from "quill-image-resize";
import { ImageDrop } from "quill-image-drop-module";
import MailTemplate from "./MailTemplate";

import 'flatpickr/dist/flatpickr.css'

let DirectionAttribute = Quill.import("attributors/attribute/direction");
let AlignStyle = Quill.import("attributors/style/align");
let SizeStyle = Quill.import("attributors/style/size");
let BackgroundStyle = Quill.import("attributors/style/background");
let ColorStyle = Quill.import("attributors/style/color");
let DirectionStyle = Quill.import("attributors/style/direction");
let FontStyle = Quill.import("attributors/style/font");

SizeStyle.whitelist = [
	"6px",
	"8px",
	"10px",
	"12px",
	"14px",
	"16px",
	"18px",
	"20px",
	"24px",
	"30px",
	"32px",
	"36px",
];

Quill.register(DirectionAttribute, true);
Quill.register(AlignStyle, true);
Quill.register(SizeStyle, true);
Quill.register(BackgroundStyle, true);
Quill.register(ColorStyle, true);
Quill.register(DirectionStyle, true);
Quill.register(FontStyle, true);

Quill.register("modules/imageResize", ImageResize);
Quill.register("modules/imageDrop", ImageDrop);

const iniData = () => {
	return {
		data: "",
		options: {
			subject: "",
			images: [],
			days_after: 0,
            start_time: '00:00',
            end_time: '23:59',
            delay_minutes: 0,
            extra: {
                envio_imediato: true,
                qualquer_horario: true
            }
		},
	}
};

const toolbarOptions = [
	["bold", "italic"],
	["blockquote"],
	[{ list: "ordered" }, { list: "bullet" }],
	[{ script: "sub" }, { script: "super" }],
	[{ direction: "rtl" }],
	[
		{
			size: [
				false,
				"10px",
				"12px",
				"14px",
				"16px",
				"18px",
				"20px",
				"24px",
				"30px",
				"32px",
				"36px",
			],
		},
	],
	[{ header: [1, 2, 3, 4, 5, 6, false] }],
	[{ color: [] }, { background: [] }],
	[{ font: [] }],
	[{ align: [] }],
	["clean"],
	["link", "image", "video"],
];

const quillOptions = () => {
	return {
		editorOptions: {
			placeholder: "Digite a mensagem a ser enviada...",
			theme: "snow",
			modules: {
				toolbar: {
					container: toolbarOptions,
					handlers: {
						image: (value) => {
							if (value) {
								document.getElementById("imageUpload").click();
							} else {
								this.quill.format("image", false);
							}
						},
					},
				},
				imageResize: {
					displayStyles: {
						backgroundColor: "black",
						border: "none",
						color: "white",
					},
					modules: ["Resize", "DisplaySize"],
				},
				history: {
					delay: 2000,
					maxStack: 500,
					userOnly: true,
				},
				imageDrop: true,
			}
		}
	}
}

export default {
	data() {
		return {
			...iniData(),
			...quillOptions(),
		};
	},
	components: {
		quillEditor,
		MailTemplate,
		flatPickr,
		NumberEdit
	},
	computed: {
		...mapState("action", [
			"id",
			"action_type_id",
			"action_sequence",
			"action_description",
			"action_data",
			"editingIndex",
		]),
		...mapState("step", ["actions", "actionEditingIndex"]),
		...mapGetters("funnel", ["GetActionTypeByName"]),
		...mapGetters("variables", ["GetVariablesAsObject", "GetVariables"]),
		actionDescription: {
			get() {
				return this.action_description;
			},
			set(value) {
				this.ActionSetActionDescription(value);
			},
		},
		isEditing() {
			return this.actionEditingIndex !== null;
		},
		editor() {
			return this.$refs.emailMessageEditor.quill;
		},
	},
	methods: {
		...mapActions("action", [
			"ActionSetId",
			"ActionSetActionTypeId",
			"ActionSetActionSequence",
			"ActionSetActionDescription",
			"ActionSetActionData",
			"ActionClearState",
		]),
		...mapActions("step", [
			"ActionAddNewAction",
			"ActionSetActionComponent",
			"ActionUpdateAction",
			"ActionSetEditActionIndex",
		]),
		saveEmailAction() {
			const {editorOptions, ...payload } = this.$data
			this.ActionSetActionData({ ...payload }).then(() => {
				if (!this.isEditing) {
					this.ActionSetId(this.$uuid())
					this.ActionAddNewAction().then(() => this.clearForm())
				} else {
					this.ActionUpdateAction().then(() => this.clearForm())
				}
			});
		},
		cancelNewEmailAction() {
			this.$swal
				.fire({
					title: "Cancelar cadastro da ação?",
					text: `Os dados informados serão perdidos...`,
					icon: "warning",
					heightAuto: false,
					showCancelButton: true,
					confirmButtonText: "Sim, Cancelar!",
					cancelButtonText: "Não, Continuar.",
				})
				.then((result) => {
					if (result.value) {
						if (this.isEditing) {
							this.ActionSetEditActionIndex(null);
						}
						this.clearForm();
					}
				});
		},
		addCustomSelectToEditor() {
			const quill = this.$refs.emailMessageEditor.quill;

			const variablesDW = new QuillToolbarDropDown({
				label: "Variáveis",
				rememberSelection: false,
			});

			variablesDW.setItems(this.GetVariablesAsObject);

			variablesDW.onSelect = function (label, value, quill) {
				const { index, length } = quill.selection.savedRange;
				quill.deleteText(index, length);
				quill.insertText(index, value);
				quill.setSelection(index + value.length);
			};

			variablesDW.attach(quill);
		},
		clearForm() {
			Object.assign(this.$data, { ...iniData(), ...quillOptions() });
			this.ActionClearState();
			this.ActionSetActionComponent(componentTypes.ACTIONS_TABLE);
		},
		insertVariable(value) {
			insertTextAtCursor(this.$refs["emailSubject"], value);
		},
		setDataFromTemplate(event) {
			this.data = event;
		},
		imageUpload(e) {
			if (e.target.files.length !== 0) {
				let quill = this.editor;
				let reader = new FileReader();
				reader.readAsDataURL(e.target.files[0]);
				let self = this;
				reader.onloadend = () => {
					let base64data = reader.result;
					self.options.images.push(base64data);

					// Get cursor location
					let length = quill.getSelection().index;

					// Insert image at cursor location
					quill.insertEmbed(length, "image", base64data);

					// Set cursor to the end
					quill.setSelection(length + 1);
				};
			}
		},
		onEditorBlur(editor) {
			//
		},
		onEditorFocus(editor) {
			//
		},
		onEditorReady(editor) {
			//
		},
	},
	mounted() {
		this.addCustomSelectToEditor();
		if (this.isEditing) {
			//let data = JSON.parse(this.action_data)
			Object.assign(this.$data, this.action_data);
		} else {
			this.actionDescription = "Enviar Email";
			this.ActionSetActionTypeId(this.GetActionTypeByName("email").id);
			this.ActionSetActionSequence((this.actions.length ?? 0) + 1);
		}
	},
};
</script>
