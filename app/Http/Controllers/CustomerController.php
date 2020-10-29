<?php

namespace App\Http\Controllers;

use App\Models\User\Customer;
use App\Models\User\Lead;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class CustomerController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'customer_name' => 'Nome',
            'customer_phone_number' => 'Telefone',
            'customer_email' => 'E-mail'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'name' => "Clientes"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'customer_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $customers = Customer::where('customer_name', 'like', "%$request->searchField%")
                                ->orWhere('customer_phone_number', 'like', "%$request->searchField%")
                                ->orWhere('customer_email', 'like', "%$request->searchField%")
                                ->orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        } else {
            $customers = Customer::orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        }

        return $this->getIndex('user.customers.index')
                    ->withCustomers($customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link'=>"/customer",
                'name'=>"Clientes"
            ],
            [
                'name'=>"Novo"
            ]
        ];

        return $this->getView('user.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'customer_name' => "required|unique:customers,customer_name,NULL,NULL,user_id,$userId",
            'customer_phone_number' => 'required',
            'customer_email' => "required|email|unique:customers,customer_name,NULL,NULL,user_id,$userId"
        ]);

        $customer = new Customer($request->all());
        $customer->save();

        return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link'=>"/customer",
                'name'=>"Clientes"
            ],
            [
                'name'=>"Alterar"
            ]
        ];

        return $this->getView('user.customers.edit')->withCustomer($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'customer_name' => "required|unique:customers,customer_name,$customer->id,id,user_id,$userId",
            'customer_phone_number' => 'required',
            'customer_email' => "required|email|unique:customers,customer_email,$customer->id,id,user_id,$userId"
        ]);

        $customer->fill($request->all());
        $customer->save();

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            return response()->json($customer->delete());
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 23000:
                    throw new Exception('Não é possível remover registro. Existem dados vinculados.');
                    break;
                default:
                    throw new Exception('Ocorreu um erro ao remover o registro.');
                    break;
            }
        }

    }

    /**
     * Get a list of customers based on the filters passed by request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCustomersJson(Request $request): JsonResponse
    {
        $query = $this->getCustomerFilters($request);
        return response()->json(
                    $query->orderBy($this->orderField, $this->orderType)
                        ->orderByDesc(
                             Lead::select('leads.created_at')
                                ->whereColumn('leads.customer_id', 'customers.id')
                                ->orderBy('leads.created_at', 'desc')
                                ->limit(1)
                        )
                        ->get()
                    );
    }

    /**
     * Return a query builder with all joins needed to run the query
     *
     * @return Builder
     */
    public function getCustomerJoins(): Builder {
        return Customer::distinct()
                        ->select('customers.*', 'products.product_name', 'customer_statuses.customer_status', 'payment_types.payment_type', DB::raw('0 as checked'))
                        ->leftJoin('leads', 'leads.customer_id', 'customers.id')
                        ->leftJoin('products', 'products.id', 'leads.product_id')
                        ->leftJoin('payment_types', 'payment_types.id', 'leads.payment_type_id')
                        ->join('customer_statuses', 'customer_statuses.id', 'customers.customer_status_id');
    }

    /**
     * Given a set of filters passed by a request, get a builder instance with the
     * filter applyed.
     *
     * @param Request $request
     * @return Builder
     */
    public function getCustomerFilters(Request $request): Builder
    {
        $query = $this->getCustomerJoins();

        if ($request->customerStatus) {
            $query->where('customers.customer_status_id', $request->customerStatus);
        }
        if ($request->dtLastLeadBegin && $request->dtLastLeadEnd) {
            $query->whereBetween('leads.created_at', [
                $this->parseBeginDate($request->dtLastLeadBegin),
                $this->parseEndDate($request->dtLastLeadEnd)
            ]);
        } else if ($request->dtLastLeadBegin && !$request->dtLastLeadEnd) {
            $query->where('leads.created_at', '>=', $this->parseBeginDate($request->dtLastLeadBegin));
        } else if (!$request->dtLastLeadBegin && $request->dtLastLeadEnd) {
            $query->where('leads.created_at', '<=', $this->parseEndDate($request->dtLastLeadEnd));
        }
        if ($request->productId) {
            $query->where('products.id', $request->productId);
        }
        if ($request->paymentTypeId) {
            $query->where('payment_types.id', $request->paymentTypeId);
        }

        return $query;
    }

    /**
     * Parse the date received, set the timezone to application timezone and set
     * the time to the start of the day
     *
     * @param string $date
     * @return object
     */
    public function parseBeginDate(string $date): object
    {
        return Carbon::parse($date)->setTimezone(config('app.timezone'))->startOfDay();
    }

    /**
     * Parse the date received, set the timezone to application timezone and set
     * the time to the end of the day
     *
     * @param string $date
     * @return object
     */
    public function parseEndDate(string $date): object
    {
        return Carbon::parse($date)->setTimezone(config('app.timezone'))->endOfDay();
    }
}
