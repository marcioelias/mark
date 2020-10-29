<template>
	<div>
		<div class="row">
			<div class="col col-sm col-md-12 col-lg-12 mb-1">
				<label for="description">Descrição</label>
				<input type="text" class="form-control" :class="{ 'is-invalid': httpErrors.hasOwnProperty('description') }" v-model="currentDescription" name="description" id="description" />
				<span v-show="httpErrors.hasOwnProperty('description')" class="invalid-feedback" style="display: block">
					<span v-for="(error, index) in httpErrors.description" :key="index">{{ error }}</span>
				</span>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-lg-4 mb-1">
				<label for="description">Produto</label>
				<Select2 v-model="currentProductId" class="w-100" :class="{ 'is-invalid': httpErrors.hasOwnProperty('product_id') }" name="product_id" id="product_id"
					:options="GetProductsForSelect" />
				<span v-show="httpErrors.hasOwnProperty('product_id')" class="invalid-feedback" style="display: block">
					<span v-for="(error, index) in httpErrors.product_id" :key="index">{{ error }}</span>
				</span>
			</div>
			<div class="col-sm-12 col-lg-4">
				<label for="start_at">Início do disparo</label>
				<datepicker
					v-model="currentStartDate"
					name="start_date"
					input-class="form-control"
					wrapper-class="mb-1"
					:language="ptBR"
					format="dd/MM/yyyy"
					:disabledDates="{
						to: new Date() //$moment().subtract(1, 'days')._d
					}">
				</datepicker>
				<!-- <input type="text" v-model="start_date" id="start_at" name="start_at" class="form-control pickadate"> -->
			</div>
			<div class="col-sm-12 col-lg-4">
				<label>Horas</label>
				<flat-pickr
					v-model="currentStartTime"
					class="form-control mb-1"
					:config="{
						enableTime: true,
						noCalendar: true,
						dateFormat: 'H:i',
						time_24hr: true
					}"
				/>
			</div>
		</div>
		<div class="divider divider-primary divider-dotted">
            <div class="divider-text">Mensagem</div>
        </div>
		<message-container></message-container>
	</div>
</template>

<style lang="css">
	div.is-invalid span.selection span.select2-selection[role=combobox] {
		border-color: #ea5455 !important;
	}
</style>

<script>
import FlatPickr from 'vue-flatpickr-component'
import Select2 from 'v-select2-component'
import moment from 'moment'
import Datepicker from 'vuejs-datepicker'
import { ptBR } from 'vuejs-datepicker/dist/locale'
import {mapActions, mapGetters, mapState} from 'vuex'
import MessageContainer from './MessageContainer'

import 'flatpickr/dist/flatpickr.css'



export default {
	data() {
		return {
			ptBR: ptBR,
		}
	},
	async mounted() {
		this.currentStartDate = new Date()
		this.currentStartTime = this.$moment().format('HH:mm')
		await this.ActionGetProducts({ vm: this })
	},
	components: {
		Select2, FlatPickr, Datepicker, MessageContainer
	},
	computed: {
		...mapState('marketingAction', [
			'httpErrors',
			'description',
			'startDate',
			'startTime',
			'products',
			'product_id',
		]),
		...mapGetters('marketingAction', [
			'GetProductsForSelect',
		]),
		currentDescription: {
			get() {
				return this.description
			},
			set(value) {
				this.ActionSetDescription(value)
			}
		},
		currentProductId: {
			get() {
				return this.product_id
			},
			set(value) {
				this.ActionSetProductId(value)
			}
		},
		currentStartDate: {
			get() {
				return this.startDate
			},
			set(value) {
				this.ActionSetStartDate(value)
			}
		},
		currentStartTime: {
			get() {
				return this.startTime
			},
			set(value) {
				this.ActionSetStartTime(value)
			}
		},
	},
	methods: {
		...mapActions('marketingAction', [
			'ActionSetDescription',
			'ActionSetProductId',
			'ActionSetStartDate',
			'ActionSetStartTime',
			'ActionGetProducts',
		])
	},
}
</script>
