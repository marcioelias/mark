<?php

namespace App\Http\Controllers;

use App\Constants\CustomerStatuses;
use App\Models\User\CustomerStatus;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CustomerStatusController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'customer_status' => 'Status',
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
                'name' => "Status de Cliente"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'customer_status',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $customerStatuses = CustomerStatus::where('customer_status', 'like', "%$request->searchField%")
                                ->orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        } else {
            $customerStatuses = CustomerStatus::orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        }

        if (isset($request->accessDenied)) {
            return $this->getIndex('user.customer_statuses.index')
                        ->withCustomerStatuses($customerStatuses)
                        ->withAccessDenied(true);
        } else {
            return $this->getIndex('user.customer_statuses.index')
                        ->withCustomerStatuses($customerStatuses);
        }

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
                'link' => "/customer_status",
                'name' => "Status de Cliente"
            ],
            [
                'name' => "Novo"
            ]
        ];

        return View('user.customer_statuses.create');
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
            'customer_status' => "required|unique:customer_statuses,customer_status,NULL,NULL,user_id,$userId,user_id,NULL",
        ]);

        $customerStatus = new CustomerStatus($request->all());
        $customerStatus->save();

        return response()->json($customerStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerStatus $customerStatus)
    {
        /* não permite edição em status padrão do sistema */
        if (!$customerStatus->user_id) {
            return Redirect::route('customer_status.index', ['accessDenied' => true]);
        }
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/customer_status",
                'name' => "Status"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        return View('user.customer_statuses.edit')
                ->withCustomerStatus($customerStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerStatus $customerStatus)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'customer_status' => "required|unique:customer_statuses,customer_status,$customerStatus->id,id,user_id,$userId,user_id,NULL"
        ]);

        $customerStatus->fill($request->all());
        $customerStatus->save();

        return response()->json($customerStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\CustomerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerStatus $customerStatus)
    {
         /* não permite exclusão em status padrão do sistema */
         if (!$customerStatus->user_id) {
            throw new Exception('Não é possível excluir um Status padrão do sistema.');
        }
        return response()->json($customerStatus->delete());
    }

    /**
     * Get all Customer Status (default and custom) for the current logged in user
     *
     * @return JsonResponse
     */
    public function getCustomerStatusJson(): JsonResponse
    {
        try {
            return response()->json(CustomerStatus::orderBy('customer_status', 'ASC')->get());
        } catch (Exception $e) {
            return response()->json([]);
        }

    }
}
