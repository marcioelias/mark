<?php

namespace App\Http\Controllers;

use App\Constants\TransactionTypes;
use App\Events\OnBuySMSPackage;
use App\MercadoPago\MercadoPago;
use App\Models\SmsPackage;
use App\Models\TransactionType;
use App\Models\User;
use App\Models\User\SmsUserTransaction;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SmsPackageController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'sms_package_name' => 'Pacote SMS',
            'sms_amount' => 'Quantidade',
            'package_value' => ['label' => 'Valor', 'type' => 'decimal', 'decimais' => 2],
            'active' => ['label' => 'Ativo', 'type' => 'bool']
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
                'name' => 'Pacotes'
            ]
        ];

        $this->setOrder($request, [
            'order_by' => 'sms_package_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $smsPackages = SmsPackage::where('sms_package_name', 'like', "%$request->searchField%")
                        ->orderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        } else {
            $smsPackages = SmsPackage::orderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        }

        return $this->getIndex('sms_packages.index')
                    ->withSmsPackages($smsPackages);
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
                'name' => 'SMS'
            ],
            [
                'link' => '/admin/sms_package',
                'name' => 'Pacotes'
            ],
            [
                'name' => 'Novo'
            ],
        ];

        return $this->getView('sms_packages.create');
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
            'sms_package_name' => 'required|unique:sms_packages',
            'sms_package_description' => 'required',
            'sms_amount' => 'required',
            'package_value' => 'required'
        ],[],
        [
            'sms_package_name' => 'Pacote',
            'sms_package_description' => 'Descrição',
            'sms_amount' => 'Quantidade de SMS',
            'package_value' => 'Valor'
        ]);

        $smsPackage = new SmsPackage($request->all());
        $smsPackage->active = ($request->active ?? false) ? true : false;
        return response()->json($smsPackage->save());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsPackage $smsPackage)
    {
        $this->breadcrumbs = [
            [
                'name' => 'SMS'
            ],
            [
                'link' => '/admin/sms_package',
                'name' => 'Pacotes'
            ],
            [
                'name' => 'Alterar'
            ],
        ];

        return $this->getView('sms_packages.edit')->withSmsPackage($smsPackage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsPackage $smsPackage)
    {
        $this->validate($request, [
            'sms_package_name' => "required|unique:sms_packages,sms_package_name,$smsPackage->id,id",
            'sms_package_description' => 'required',
            'sms_amount' => 'required',
            'package_value' => 'required'
        ],[],
        [
            'sms_package_name' => 'Pacote',
            'sms_package_description' => 'Descrição',
            'sms_amount' => 'Quantidade de SMS',
            'package_value' => 'Valor'
        ]);

        $smsPackage->fill($request->all());
        $smsPackage->active = ($request->active ?? false) ? true : false;
        return response()->json($smsPackage->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsPackage  $smsPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsPackage $smsPackage)
    {
        //
    }

    public function buyPackage() {
        if (Auth::user()->profileComplete()) {
            $packages = SmsPackage::where('active', true)
                                ->orderBy('sms_amount', 'ASC')
                                ->get();
            return $this->getView('sms_packages.buy_package')
                        ->withMercadoPago(new MercadoPago())
                        ->withPackages($packages);
        } else {
            return redirect()->route('user.profile')->withErrors(['msg' => 'Antes de efetuar compras, por favor complete seus dados!']);
        }
    }

    public function buyPackageResponse(String $response) {
        switch ($response) {
            case 'success':
                return redirect()->route('index');
                break;

            case 'failure':
                return redirect()->route('sms.buy');
                break;

            case 'pending':
                return redirect()->route('index');
                break;

        }
    }

    public function SellPackageView() {
        $packages = SmsPackage::where('active', true)
                                ->orderBy('sms_amount', 'ASC')
                                ->get();
        $users = User::where('active', true)
                    ->orderBy('name', 'ASC')
                    ->get();
        return $this->getView('sms_packages.sell_package')
                    ->withPackages($packages)
                    ->withUsers($users);
    }

    public function SellPackage(Request $request) {

    }
}
