<?php

namespace App\Http\Controllers;

use App\Constants\SmsTransactionObs;
use App\Constants\TransactionTypes;
use App\Models\SmsPackage;
use App\Models\SmsSale;
use App\Models\SmsStock;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\User\Customer;
use App\Models\User\SmsUserTransaction;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsSaleController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'created_at' => ['label' => 'Data da Venda', 'type' => 'datetime'],
            'name' => 'Cliente',
            'sms_package_name' => 'Pacote',
            'quantity' => 'Quantidade',
            //'paid_amount' => ['label' => 'Valor', 'type' => 'decimal', 'decimais' => 2]
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
                'name' => 'SMS'
            ],
            [
                'name' => 'Venda'
            ]
        ];

        $this->setOrder($request, [
            'order_by' => 'created_at',
            'order_type' => 'DESC'
        ]);

        if ($request->searchField) {
            $smsSales = SmsSale::select('sms_sales.*',
                                        'sms_user_transactions.quantity',
                                        'users.name',
                                        'sms_packages.sms_package_name')
                                ->join('sms_user_transactions', 'sms_user_transactions.id', 'sms_sales.sms_user_transaction_id')
                                ->join('users', 'users.id', 'sms_user_transactions.user_id')
                                ->join('sms_packages', 'sms_packages.id', 'sms_user_transactions.sms_package_id')
                                ->where('users.name', 'like', "%$request->searchField%")
                                ->orWhere('sms_packages.sms_package_name', 'like', "%$request->searchField%")
                                ->orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        } else {
            $smsSales = SmsSale::select('sms_sales.*',
                                        'sms_user_transactions.quantity',
                                        'users.name',
                                        'sms_packages.sms_package_name')
                                ->join('sms_user_transactions', 'sms_user_transactions.id', 'sms_sales.sms_user_transaction_id')
                                ->join('users', 'users.id', 'sms_user_transactions.user_id')
                                ->join('sms_packages', 'sms_packages.id', 'sms_user_transactions.sms_package_id')
                                ->orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        }

        Log::debug($smsSales);
        return $this->getIndex('sms_sale.index')
                    ->withSmsSales($smsSales);
    }


    /* Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $this->breadcrumbs = [
            [
                'name' => 'SMS'
            ],
            [
                'link' => '/admin/sms_sale',
                'name' => 'Vendas'
            ],
            [
                'name' => 'Nova'
            ],
        ];

        $smsPackages = SmsPackage::where('active', true)->orderBy('sms_package_name', 'asc')->get();
        $users = User::where('active', true)->orderBy('name', 'asc')->get();

        return $this->getView('sms_sale.create')
                    ->withSmsPackages($smsPackages)
                    ->withUsers($users);
    }

    /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required',
            'sms_package_id' => 'required'
        ],[],
        [
            'user_id' => 'Cliente',
            'sms_package_id' => 'Pacote SMS'
        ]);

        DB::beginTransaction();
        $smsPackage = SmsPackage::find($request->sms_package_id);
        try {
            $smsUserTransaction = SmsUserTransaction::create([
                'user_id' => $request->user_id,
                'quantity' => $smsPackage->sms_amount,
                'transaction_type_id' => TransactionTypes::IN,
                'obs' => SmsTransactionObs::SMS_OBS_MANUAL,
                'sms_package_id' => $request->sms_package_id,
                'paid_amount' => $smsPackage->package_value
            ]);

            $smsSale = new SmsSale($request->all());
            $smsSale->sms_user_transaction_id = $smsUserTransaction->id;
            $smsSale->save();

            $smsStockOut = new SmsStock([
                'amount' => $smsSale->smsPackage->sms_amount,
                'move_in' => false
            ]);

            $result = $smsSale->smsStock()->save($smsStockOut);

            if ($result) {
                DB::commit();
                return $result;
            } else {
                throw new Exception('Ocorreu um erro ao efetivar o cadastro');
            }
            
        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsSale  $smsSale
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsSale $smsSale)
    {
        return response()->json($smsSale->smsUserTransaction()->delete());
    }
}
