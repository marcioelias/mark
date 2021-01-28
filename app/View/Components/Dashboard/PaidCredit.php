<?php

namespace App\View\Components\Dashboard;

use App\Constants\LeadStatuses;
use App\Constants\PaymentTypes;
use App\Models\User\Lead;
use App\Models\User\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class PaidCredit extends Component
{

    public $paidLeads = 0;
    public $allLeads = 0;
    public $percentual = 0;
    public $paidAmount = 0;
    public $series = [];
    public $labels = [];
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
        return view('components.dashboard.paid-credit');
    }

    public function getData() {
        $startDate = Carbon::now()->startOfMonth()->startOfDay();
        $endDate = Carbon::now()->endOfMonth()->endOfDay();

        $products =  $this->getProducts($startDate, $endDate);
        $this->paidLeads = $this->getLeadsCount($startDate, $endDate, NULL, LeadStatuses::APPROVED);
        $this->allLeads = $this->getLeadsCount($startDate, $endDate);
        foreach ($products as $product) {
            $countPaid = $this->getLeadsCount($startDate, $endDate, $product, LeadStatuses::APPROVED); 

            $this->labels[] = $product->product_name;
            $this->series[] = round(($countPaid == 0 ? 0 : ($countPaid * 100) / $this->paidLeads), 1);
        }

        $this->paidAmount = $this->getLeadsAmount($startDate, $endDate, LeadStatuses::APPROVED);
        $this->percentual = $this->paidLeads == 0 ? 0 : ($this->paidLeads * 100) / $this->allLeads;

    }

    private function getProducts(Carbon $startDate, Carbon $endDate) {
        return Product::whereHas('leads', function(Builder $query) use($startDate, $endDate) {
            $query->whereBetween('leads.created_at', [$startDate, $endDate]);
        })->get();
    }

    private function getLeadsCount(Carbon $startDate, Carbon $endDate, Product $product = null, string $status = null) {
        return Lead::where('payment_type_id', PaymentTypes::CARTAO_CREDITO)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where(function(Builder $query) use ($product) {
                        if ($product) {
                            return $query->where('product_id', $product->id);
                        }
                    })
                    ->where(function(Builder $query) use ($status) {
                        if ($status) {
                            return $query->where('lead_status_id', $status);
                        } 
                    })
                    ->count();
    }

    private function getLeadsAmount(Carbon $startDate, Carbon $endDate, string $status = null) {
        return Lead::where('payment_type_id', PaymentTypes::CARTAO_CREDITO)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->where(function(Builder $query) use ($status) {
                        if ($status == null) {
                            return $query->whereRaw('1 = 1');
                        } else {
                            return $query->where('lead_status_id', $status);
                        }
                        
                    })
                    ->sum('value');
    }
}
