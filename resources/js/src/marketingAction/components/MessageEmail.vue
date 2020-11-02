<template>
    <div>
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
                            v-model="currentSubject"
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
                    <mail-template :message="currentMessage" :images="currentImages" @templateLoaded="setDataFromTemplate" />
                </div>
                <div class="d-flex align-items-end ml-2">
                    <button class="btn btn-danger"><i class="fa fa-times" @click="cancelar"></i> Cancelar</button>
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
                    v-model="currentMessage"
                    rows="10"
                    :options="editorOptions"
                    @blur="onEditorBlur($event)"
                    @focus="onEditorFocus($event)"
                    @ready="onEditorReady($event)"
                ></quill-editor>
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

import insertTextAtCursor from "insert-text-at-cursor";
import Quill from "quill";
import ImageResize from "quill-image-resize";
import { ImageDrop } from "quill-image-drop-module";
import MailTemplate from "../../action/components/MailTemplate";

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
	},
	computed: {
        ...mapGetters("variables", ["GetVariablesAsObject", "GetVariables"]),
		editor() {
			return this.$refs.emailMessageEditor.quill;
		},
		...mapState('marketingAction', [
			'message'
		]),
		currentMessage: {
            get() {
                return this.message.data
            },
            set(value) {
                this.ActionSetMessage(value)
            }
		},
		currentSubject: {
			get() {
				return this.message.options.subject
			},
			set(value) {
				this.ActionSetEmailSubject(value)
			}
		},
		currentImages: {
			get() {
				return this.message.options.images
			},
			set(value) {
				this.ActionAddEmailMessageImage(value)
			}
		}
	},
	methods: {
        ...mapActions('marketingAction', [
			'ActionSetMessageType',
			'ActionSetMessage',
			'ActionAddEmailMessageImage',
			'ActionSetEmailSubject',
			'ActionCancelMessage'
        ]),
		cancelar() {
			this.$swal
				.fire({
					title: "Cancelar Mensagem de E-mail?",
					text: `Os dados informados serão perdidos...`,
					icon: "warning",
					heightAuto: false,
					showCancelButton: true,
					confirmButtonText: "Sim, Cancelar!",
					cancelButtonText: "Não, Continuar.",
				})
				.then((result) => {
					if (result.value) {
                        this.ActionCancelMessage()
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
					self.ActionAddEmailMessageImage(base64data);

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
			// console.log('editor blur!', editor)
		},
		onEditorFocus(editor) {
			// console.log('editor focus!', editor)
		},
		onEditorReady(editor) {
			// console.log('editor ready!', editor)
		},
	},
	mounted() {
		this.addCustomSelectToEditor();
		if (this.isEditing) {
			//let data = JSON.parse(this.action_data)
			Object.assign(this.$data, this.action_data);
		}
	},
};
</script>
