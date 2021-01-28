<?php

namespace App\View\Components\Dashboard;

use App\Constants\ActionTypes;
use App\Models\User\SentMessage;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\View\Component;

class TotalWhatsappMessagesSent extends Component
{

    public $total = 0;
    public $series = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getWhatsappMessagesSent();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.total-whatsapp-messages-sent');
    }

    private function getWhatsappMessagesSent() {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();
        while ($startDate <= $endDate) {
            $totalOfDay = $this->getWhatsappMessagesSentByDay(new CarbonImmutable($startDate));
            $this->total += $totalOfDay;
            $this->series[] = [$startDate->timestamp, $totalOfDay];
            //$this->series[] = $totalOfDay;
            $startDate->addDay();
        }
    }

    private function getWhatsappMessagesSentByDay(CarbonImmutable $date) {
        $startOfDay = $date->startOfDay();
        $endOfDay = $date->endOfDay();
        return SentMessage::join('funnel_step_actions', 'sent_messages.funnel_step_action_id', 'funnel_step_actions.id')
                ->where('funnel_step_actions.action_type_id', ActionTypes::WHATSAPP)
                ->where('sent_messages.is_successful', true)
                ->whereBetween('sent_messages.created_at', [$startOfDay, $endOfDay])
                ->count();
    }
}
