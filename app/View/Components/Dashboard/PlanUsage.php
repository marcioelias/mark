<?php

namespace App\View\Components\Dashboard;

use App\Constants\ActionTypes;
use App\Constants\FeatureTypes;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class PlanUsage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.plan-usage');
    }

    public function getStatistics() {
        $features = Auth::user()->plan->features->sortBy('order');
        $startDate = Auth::user()->activated_at;
        $stats = [];
        foreach ($features as $feature) {
            $stats[] = [
                'feature' => $feature->feature,
                'enabled' => $feature->pivot->enabled,
                'limit' => $feature->pivot->limit,
                'usage' => $this->featureUsage($feature->id, $startDate) ?? now()
            ];
        }

        return $stats;
    }

    private function featureUsage(string $featureType, DateTime $startAt) {
        switch ($featureType) {
            case FeatureTypes::POSTBACKS:
                return $this->postbackStats($startAt);
                break;

            case FeatureTypes::LEADS:
                return $this->leadStats($startAt);
                break;

            case FeatureTypes::POSTBACKS:
                return $this->postbackStats($startAt);
                break;

            case FeatureTypes::EMAILS:
                return $this->emailStats($startAt);
                break;

            case FeatureTypes::SMS:
                return $this->smsStats($startAt);
                break;

            case FeatureTypes::WHATSAPP:
                return $this->whatsappStats($startAt);
                break;
        }
    }

    private function postbackStats(DateTime $startDate) {
        return Auth::user()->postbacks->where('created_at', '>=', $startDate)->count() ?? 0;
    }

    private function leadStats(DateTime $startDate) {
        return Auth::user()->leads->where('created_at', '>=', $startDate)->count() ?? 0;
    }

    private function emailStats(DateTime $startDate) {
        return Auth::user()->actions
                           ->where('executed_at', '>=', $startDate)
                           ->where('action_type_id', '=', ActionTypes::EMAIL)
                           ->count() ?? 0;
    }

    private function smsStats(DateTime $startDate) {
        return Auth::user()->actions
                           ->where('executed_at', '>=', $startDate)
                           ->where('action_type_id', '=', ActionTypes::SMS)
                           ->count() ?? 0;
    }

    private function whatsappStats(DateTime $startDate) {
        return Auth::user()->leads->where('created_at', '>=', $startDate)->count() ?? 0;
    }
}
