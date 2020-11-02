<template>
	<div class="d-flex justify-content-around">
		<button
			class="btn btn-sm pl-1 pr-1"
			v-for="schedule in orderedSchedules"
			:key="schedule.id"
			:class="buttonStyle(schedule)"
			v-tooltip:top="getTooltipMsg(schedule)"
			@click="ActionSetCurrentSchedule(schedule)"
		>
			<i :class="buttonIcon(schedule)"></i>
		</button>

	</div>
</template>

<script>

/* data-toggle="modal"
data-target="#notificationSentModal"
 */
import { SCHEDULE, ACTION } from "../../../user/constants"
import { mapActions } from 'vuex'

export default {
	props: {
		lead: {
			type: Object,
			required: true,
		},
	},
	computed: {
		orderedSchedules() {
			return this.lead.schedules.sort(
				(a, b) => a.action.action_sequence - b.action.action_sequence
			);
		},
	},
	methods: {
		...mapActions('funnelShow', [
			'ActionSetCurrentSchedule'
		]),
		buttonStyle(schedule) {
			return {
				"btn-success": schedule.schedule_status_id === SCHEDULE.FINISHED,
				"btn-secondary": schedule.schedule_status_id === SCHEDULE.PENDING,
				"btn-warning": schedule.schedule_status_id === SCHEDULE.CANCELED,
				"btn-danger": schedule.schedule_status_id === SCHEDULE.ERROR,
			};
		},
		buttonIcon(schedule) {
			return {
				"fas fa-envelope": schedule.action.action_type_id === ACTION.EMAIL,
				"fa fa-sms": schedule.action.action_type_id === ACTION.SMS,
			};
		},
		getTooltipMsg(schedule) {
			let statusMsg = this.scheduleStatusStr(schedule);
			let actionMsg = this.actionTypeStr(schedule.action);
			return `Envio de ${actionMsg} ${statusMsg}.`;
		},
		scheduleStatusStr(schedule) {
			switch (schedule.schedule_status_id) {
				case SCHEDULE.FINISHED:
					return "finalizado";
					break;
				case SCHEDULE.PENDING:
					return "pendente";
					break;
				case SCHEDULE.CANCELED:
					return "cancelado";
					break;
				case SCHEDULE.ERROR:
					return "com erro";
					break;
			}
		},
		actionTypeStr(action) {
			switch (action.action_type_id) {
				case ACTION.EMAIL:
					return "E-mail";
					break;
				case ACTION.SMS:
					return "SMS";
					break;
			}
		},
		showNotification() {

		}
	},
};
</script>
