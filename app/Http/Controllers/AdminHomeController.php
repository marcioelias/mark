<?php

namespace App\Http\Controllers;

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
        $startDate = Carbon::now()->subMonths(6)->firstOfMonth();

        return User::select(
                        DB::raw("date_format(users.created_at, '%m/%Y') as mes_formatado"),
                        DB::raw("date_format(users.created_at, '%Y-%m') as mes"),
                        DB::raw("sum(plans.plan_value) as total"))
                    ->leftJoin('plans', 'plans.id', '=', 'users.plan_id')
                    ->where('users.created_at', '>=', $startDate)
                    ->groupBy(['mes', 'mes_formatado'])
                    ->toSQL()
                    ->get()
                    ->toArray();

    }
}
