<?php

namespace App\Http\Controllers;

use App\Constants\TransactionTypes;
use App\Models\Plan;
use App\Models\SmsBuy;
use App\Models\User;
use App\Models\User\SmsUserTransaction;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminHomeController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs = [];
    public $smsBuyed = 0;
    public $smsSales = 0;

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
        $this->getSmsBuyed();
        $this->getSmsSales();
        return array_merge(
            ['clientByStatus' => $this->getClientStatistics()],
            ['monthlyInvoicing' => $this->getMounthlyInvoicing()],
            ['smsAdquiridos' => $this->smsBuyed],
            ['smsVendidos'=> $this->smsSales],
            ['smsSaldo' => $this->getSmsBalance()]
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

    public function getSmsBuyed() {
        $startDate = Carbon::now()->subMonth();
        $this->smsBuyed = SmsBuy::where('created_at', '>=', $startDate)->sum('amount');
    }

    public function getSmsSales() {
        $startDate = Carbon::now()->subMonth();
        $this->smsSales = SmsUserTransaction::where('created_at', '>=', $startDate)
                            ->where('transaction_type_id', TransactionTypes::IN)
                            ->sum('quantity');
    }

    public function getSmsBalance() {
        $buy = SmsBuy::sum('amount');
        $sale = SmsUserTransaction::where('transaction_type_id', TransactionTypes::IN)->sum('quantity');
        return $buy - $sale;
    }
}
