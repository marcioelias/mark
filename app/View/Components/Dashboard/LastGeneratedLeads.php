<?php

namespace App\View\Components\Dashboard;

use App\Constants\LeadStatuses;
use App\Models\User\Lead;
use Illuminate\View\Component;

class LastGeneratedLeads extends Component
{
    public $leads;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->leads = $this->getLastLeads();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.last-generated-leads');
    }

    private function getLastLeads() {
        return Lead::with('customer', 'product', 'paymentType')
                    ->where('lead_status_id', LeadStatuses::APPROVED)
                    ->orderBy('paid_at', 'desc')
                    ->limit(10)
                    ->get();
    }
}
