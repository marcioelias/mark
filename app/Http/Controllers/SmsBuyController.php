<?php

namespace App\Http\Controllers;

use App\Models\SmsBuy;
use App\Models\SmsStock;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsBuyController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'created_at' => ['label' => 'Data da Compra', 'type' => 'datetime'],
            'amount' => 'Quantidade',
            'unitary_value' => ['label' => 'Vlr. Unitário', 'type' => 'decimal', 'decimais' => 3]
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
                'name' => 'Compras'
            ]
        ];

        $this->setOrder($request, [
            'order_by' => 'created_at',
            'order_type' => 'DESC'
        ]);

        if ($request->searchField) {
            $smsBuys = SmsBuy::where('amount', $request->searchField)
                        ->orWhere('unitary_value', $request->searchField)
                        ->orderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        } else {
            $smsBuys = SmsBuy::orderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        }

        return $this->getIndex('sms_buys.index')
                    ->withSmsBuys($smsBuys);
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
                'link' => '/admin/sms_buy',
                'name' => 'Compras'
            ],
            [
                'name' => 'Nova'
            ],
        ];

        return $this->getView('sms_buys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $smsBuy = new SmsBuy($request->all());
        DB::beginTransaction();
        try {
            $smsBuy->save();

            $smsStockIn = new SmsStock([
                'amount' => $smsBuy->amount,
                'move_in' => true
            ]);

            $result = $smsBuy->smsStock()->save($smsStockIn);

            DB::commit();

            return response()->json($result);
        } catch (\Exception $e) {
            Log::critical("Erro ao salvar registro de compra de SMS.".$e->getMessage());
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SmsBuy  $smsBuy
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsBuy $smsBuy)
    {
        return response()->json($smsBuy->delete());
    }
}
