<?php

namespace App\View\Components\Dashboard;

use App\Models\User\Lead;
use Carbon\Carbon;
use Illuminate\View\Component;

class MonthlySale extends Component
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
        return $this->getData();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.monthly-sale');
    }

    public function getData() {
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfMonth()->endOfDay();

        $this->series = $this->getDailyConversion($startDate, $endDate);

        $lastDay = $endDate->day;
        for ($i=1; $i <= $lastDay; $i++) { 
           $aux[] = round($this->series[$i] ?? 0.00, 2);
           $this->total += $aux[$i-1];
        }

        $this->series = $aux;
    }

    public function getDailyConversion(Carbon $startDate, Carbon $endDate) {
        return Lead::select('paid_at', 'value')
                            ->whereBetween('paid_at', [$startDate, $endDate])
                            ->get()
                            ->groupBy(function($row) { 
                                return Carbon::parse($row->paid_at)->day; 
                            })
                            ->map(
                                function($row) { 
                                    return $row->sum('value'); 
                            })
                            ->sortKeys();
    }
}
