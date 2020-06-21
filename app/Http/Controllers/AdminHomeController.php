<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminHomeController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs = [];

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->breadcrumbs = [
            [
                'name' => "Home"
            ],
        ];
        //dd($this->getStatistics());
        return $this->getView('dashboard.admin.dashboard')
                    ->withStats($this->getStatistics());
    }

    public function getStatistics() {
        return array_merge(
            ['clientByStatus' => $this->getClientStatistics()],
            ['monthlyInvoicing' => $this->getMounthlyInvoicing()]
        );
    }

    public function getClientStatistics() {
        return User::select('active', DB::raw('count(*) as total'))
                    ->groupBy('active')
                    ->get()
                    ->toArray();
    }

    public function getMounthlyInvoicing() {
        $startMonth = 6;
        $startDate = Carbon::now()->subMonths(6)->firstOfMonth();
        $endDate = Carbon::now()->subMonths(6)->lastOfMonth();
        $result = [];

        for ($i = $startMonth; $i > 0; $i--) {
            $result['labels'][] = $endDate->format('Y-m');
            $result['data'][] = number_format(User::select(
                DB::raw('sum(plans.plan_value) as faturamento'))
                ->join('plans', 'users.plan_id', 'plans.id')
                ->whereBetween('users.created_at', [$startDate, $endDate])
                ->where('users.active', true)
                ->first()->faturamento, 2, ',', '.');;
                $endDate->firstOfMonth()->addMonth()->lastOfMonth();
        }

        return response()->json($result);
    }

    public function getCustomerByPlan() {
        $result = [];
        $plans = Plan::orderBy('plan_name', 'asc')->get();
        foreach ($plans as $plan) {
            $result['labels'][] = $plan->plan_name;
            $result['data'][] = User::where('plan_id', $plan->id)->count();
        }

        return response()->json($result);
    }
}
