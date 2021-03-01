<?php

namespace App\View\Components\Dashboard;

use App\Constants\ActionTypes;
use App\Constants\FeatureTypes;
use App\Constants\TransactionTypes;
use App\Models\User\SmsUserTransaction;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class PlanUsageStatistics extends Component
{
    public $planStats = [];
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getStatistics();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.plan-usage-statistics');
    }

    public function getStatistics() {
        $features = Auth::user()->plan->features->sortBy('order');
        $startDate = Auth::user()->activated_at ?? now();
        $this->planStats = [
            'SMS' => [
                'enabled' => $features->where('id', FeatureTypes::SMS)->first()->pivot->enabled,
                'limit' => $features->where('id', FeatureTypes::SMS)->first()->pivot->limit + $this->SmsPackagesBuy($startDate),
                'usage' => $this->featureUsage(FeatureTypes::SMS, $startDate) ?? 0,
                'percent' => $this->getPercent($features->where('id', FeatureTypes::SMS)->first()->pivot->limit, $this->featureUsage(FeatureTypes::SMS, $startDate) ?? 0)
            ],
            'WHATSAPP' => [
                'enabled' => $features->where('id', FeatureTypes::WHATSAPP)->first()->pivot->enabled,
                'limit' => $features->where('id', FeatureTypes::WHATSAPP)->first()->pivot->limit,
                'usage' => $this->featureUsage(FeatureTypes::WHATSAPP, $startDate) ?? 0,
                'percent' => $this->getPercent($features->where('id', FeatureTypes::WHATSAPP)->first()->pivot->limit, $this->featureUsage(FeatureTypes::WHATSAPP, $startDate) ?? 0)
            ],
            'EMAIL' => [
                'enabled' => $features->where('id', FeatureTypes::EMAILS)->first()->pivot->enabled,
                'limit' => $features->where('id', FeatureTypes::EMAILS)->first()->pivot->limit,
                'usage' => $this->featureUsage(FeatureTypes::EMAILS, $startDate) ?? 0,
                'percent' => $this->getPercent($features->where('id', FeatureTypes::EMAILS)->first()->pivot->limit, $this->featureUsage(FeatureTypes::EMAILS, $startDate) ?? 0)
            ],
            'LEADS' => [
                'enabled' => $features->where('id', FeatureTypes::LEADS)->first()->pivot->enabled,
                'limit' => $features->where('id', FeatureTypes::LEADS)->first()->pivot->limit,
                'usage' => $this->featureUsage(FeatureTypes::LEADS, $startDate) ?? 0,
                'percent' => $this->getPercent($features->where('id', FeatureTypes::LEADS)->first()->pivot->limit, $this->featureUsage(FeatureTypes::LEADS, $startDate) ?? 0)
            ]
        ];
    }

    private function featureUsage(string $featureType, DateTime $startAt) {
        switch ($featureType) {
            case FeatureTypes::LEADS:
                return $this->leadStats($startAt);
                break;

            case FeatureTypes::EMAILS:
                return $this->emailStats($startAt);
                break;

            case FeatureTypes::SMS:
                return $this->smsStats($startAt);
                break;

            case FeatureTypes::WHATSAPP:
                return $this->whatsappStats();
                break;
        }
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

    private function whatsappStats() {
        return Auth::user()->whatsappInstances->count() ?? 0;
    }

    private function getPercent(int $limit, int $usage) {
        if ($limit === 0) return 0;
        return (int) max(($usage*100) / max($limit, 1), 0);
    }

    private function SmsPackagesBuy(DateTime $startAt) {
        return SmsUserTransaction::where('transaction_type_id', TransactionTypes::IN)
                                ->where('created_at', '>=', $startAt)
                                ->sum('quantity');
    }
}