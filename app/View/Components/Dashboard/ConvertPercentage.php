<?php

namespace App\View\Components\Dashboard;

use App\Constants\LeadStatuses;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ConvertPercentage extends Component
{
    public $percent;
    public $leads;
    public $converted;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $startDate = Carbon::now()->subDays(30);
        $this->leads = $this->getLeads($startDate);
        $this->converted = $this->getConvertedLeads($startDate);
        $this->percent = $this->getPercent();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.convert-percentage');
    }
    private function getLeads(DateTime $startDate) {
        return Auth::user()->leads
                            ->where('created_at', '>=', $startDate)
                            ->count() ?? 0;
    }

    private function getConvertedLeads(DateTime $startDate) {
        return Auth::user()->leads
                            ->where('created_at', '>=', $startDate)
                            ->where('lead_status_id', LeadStatuses::APPROVED)
                            ->count() ?? 0;
    }

    private function getPercent() {
        return $this->leads > 0 ? round(($this->converted * 100) / $this->leads, 1) : 0;
    }

}
