<?php

namespace App\Http\Controllers;

use App\Models\User\Customer;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
