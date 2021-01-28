<?php

namespace App\View\Components\Dashboard;

use App\Models\User\Customer;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\View\Component;

class TotalCustomers extends Component
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
        $this->getCustomers();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.dashboard.total-customers');
    }

    private function getCustomers() {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();
        while ($startDate <= $endDate) {
            $totalOfDay = $this->getCustomersByDay(new CarbonImmutable($startDate));
            $this->total += $totalOfDay;
            $this->series[] = [$startDate->timestamp, $totalOfDay];
            //$this->series[] = $totalOfDay;
            $startDate->addDay();
        }
    }

    private function getCustomersByDay(CarbonImmutable $date) {
        $startOfDay = $date->startOfDay();
        $endOfDay = $date->endOfDay();
        return Customer::whereBetween('created_at', [$startOfDay, $endOfDay])
                ->count();
    }
}
