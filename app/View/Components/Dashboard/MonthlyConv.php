<?php

namespace App\View\Components\Dashboard;

use App\Constants\LeadStatuses;
use App\Models\User\Lead;
use App\Models\User\LeadStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class MonthlyConv extends Component
{
    public $total = 0;
    public $conversion = [];
    public $leads = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getData();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.monthly-conv');
    }

    public function getData() {
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfMonth()->endOfDay();

        $conversion = $this->getDailyConversion($startDate, $endDate);
        $leadsCount = $this->getLeadsCount($startDate, $endDate);

        $lastDay = $endDate->day;
        for ($i=1; $i <= $lastDay; $i++) { 
            $aux[] = $conversion[$i] ?? 0;
            $this->total += $conversion[$i] ?? 0;
        }

        $this->conversion = $aux;

        for ($i=1; $i <= $lastDay; $i++) { 
            $aux1[] = $leadsCount[$i] ?? 0;
        }

        $this->leads = $aux1;
    }

    public function getDailyConversion(Carbon $startDate, Carbon $endDate) {
        return Lead::select('created_at')
                            ->whereBetween('created_at', [$startDate, $endDate])
                            ->where('lead_status_id', LeadStatuses::APPROVED)
                            ->get()
                            ->groupBy(function($row) { 
                                return Carbon::parse($row->created_at)->day; 
                            })
                            ->map(
                                function($row) { 
                                    return $row->count(); 
                            })
                            ->sortKeys()
                            ->toArray();
    }

    private function getLeadsCount(Carbon $startDate, Carbon $endDate) {
        // return Lead::whereBetween('created_at', [$startDate, $endDate])
        //             ->count();
        return Lead::select('created_at')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get()
                    ->groupBy(function($row) { 
                        return Carbon::parse($row->created_at)->day; 
                    })
                    ->map(
                        function($row) { 
                            return $row->count(); 
                    })
                    ->sortKeys()
                    ->toArray();
    }

}
