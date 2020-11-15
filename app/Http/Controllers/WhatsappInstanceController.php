<?php

namespace App\Http\Controllers;

use App\Constants\WppInstStatuses;
use App\Events\OnCreateWhatsappInstance;
use App\Jobs\CreateWhatsappInstanceJob;
use App\Models\User\Product;
use App\Models\User\WhatsappInstance;
use App\Models\WhatsappInstanceStatus;
use App\Traits\LayoutConfigTrait;
use App\Whatsapp\WhatsappIntegration;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WhatsappInstanceController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'description' => 'Descrição',
            'product_name' => 'Produto',
            'whatsapp_instance_status' => 'Status'
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
                'name' => 'Configurações'
            ],
            [
                'name' => "Instâncias Whatsapp"
            ],
        ];


        $this->setOrder($request, [
            'order_by' => 'description',
            'order_type' => 'asc'
        ]);

        if ($request->searchField) {
            $whatsappInstances = WhatsappInstance::select('whatsapp_instances.*', 'products.product_name', 'whatsapp_instance_statuses.whatsapp_instance_status')
                                    ->leftJoin('products', 'products.id', 'whatsapp_instances.product_id')
                                    ->join('whatsapp_instance_statuses', 'whatsapp_instance_statuses.id', 'whatsapp_instances.whatsapp_instance_status_id')
                                    ->where('products.product_name', 'like', "%$request->searchField%")
                                    ->orWhere('whatsapp_instances.description', 'like', "%$request->searchField%")
                                    ->orderBy($this->orderField, $this->orderType)
                                    ->paginate($this->paginate);
        } else {
            $whatsappInstances = WhatsappInstance::select('whatsapp_instances.*', 'products.product_name', 'whatsapp_instance_statuses.whatsapp_instance_status')
                                    ->leftJoin('products', 'products.id', 'whatsapp_instances.product_id')
                                    ->join('whatsapp_instance_statuses', 'whatsapp_instance_statuses.id', 'whatsapp_instances.whatsapp_instance_status_id')
                                    ->orderBy($this->orderField, $this->orderType)
                                    ->paginate($this->paginate);
        }

        return $this->getIndex('user.whatsapp_instances.index')
                    ->withWhatsappInstances($whatsappInstances);

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
                'name' => 'Configurações'
            ],
            [
                'link' => "/whatsapp_instance",
                'name' => "Instâncias Whatsapp"
            ],
            [
                'name' => "Novo"
            ]
        ];

        $products = Product::doesntHave('whatsappInstance')
                            ->where('active', true)
                            ->orderBy('product_name', 'asc')
                            ->get();

        return $this->getView('user.whatsapp_instances.create')
                    ->withProducts($products);
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
            'description' => "required|unique:whatsapp_instances,description,NULL,NULL,user_id,$userId",
        ]);

        DB::beginTransaction();
        try {
            $whatsappInstance = new WhatsappInstance($request->all());
            $whatsappInstance->whatsapp_instance_status_id = WppInstStatuses::PENDING;
            $whatsappInstance->port = $this->getNewInstancePort();

            $whatsappInstance->save();

            CreateWhatsappInstanceJob::dispatch($whatsappInstance);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::emergency($e->getMessage());
            return $e->getMessage();
        }



        return response()->json($whatsappInstance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WhatsappInstance  $whatsappInstance
     * @return \Illuminate\Http\Response
     */
    public function edit(WhatsappInstance $whatsappInstance)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Configurações'
            ],
            [
                'link' => "/whatsapp_instance",
                'name' => "Instâncias Whatsapp"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        $products = Product::where(function($query) use ($whatsappInstance) {
                                $query->doesntHave('whatsappInstance')
                                      ->orWhere('id', $whatsappInstance->product_id);
                            })
                            ->where('active', true)
                            ->orderBy('product_name', 'asc')
                            ->get();

        return $this->getView('user.whatsapp_instances.edit')
                    ->withWhatsappInstance($whatsappInstance)
                    ->withWppInstStatuses(new WppInstStatuses)
                    ->withProducts($products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WhatsappInstance  $whatsappInstance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhatsappInstance $whatsappInstance)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'description' => "required|unique:whatsapp_instances,description,$whatsappInstance->id,id,user_id,$userId",
        ]);

        if ($request->active && $whatsappInstance->whatsapp_instance_status_id == WppInstStatuses::DISABLED) {
            $whatsappInstance->whatsapp_instance_status_id = WppInstStatuses::DISCONNECTED;
        }

        if (!$request->active) {
            $whatsappInstance->whatsapp_instance_status_id = WppInstStatuses::DISABLED;
        }
        $whatsappInstance->description = $request->description;
        $whatsappInstance->product_id = $request->product_id;
        $whatsappInstance->save();

        if (!$whatsappInstance->url) {
            CreateWhatsappInstanceJob::dispatch($whatsappInstance);
        }

        return response()->json($whatsappInstance);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WhatsappInstance  $whatsappInstance
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhatsappInstance $whatsappInstance)
    {
        return response()->json($whatsappInstance->delete());
    }

    private function getNewInstancePort() {
        $lastPort = WhatsappInstance::max('port');
        return $lastPort ? $lastPort + 1 : config('whatsapp-api.port_start');
    }

    public function getQrCode(WhatsappInstance $whatsappInstance) {
        $qrcode = (new WhatsappIntegration($whatsappInstance))->getQrCode();
        return response()->json(['qrcode' => $qrcode]);
    }

    public function setStatus(Request $request) {
        try {
            $whatsappInstance = WhatsappInstance::subdomain($request->PASTA)
                                            ->hash($request->PASSWORD)
                                            ->firstOrFail();
            if ($whatsappInstance) {
                $whatsappInstance->whatsapp_instance_status_id = $request->STATUS === 'CONECTADO' ? WppInstStatuses::CONNECTED : WppInstStatuses::DISCONNECTED;
                $whatsappInstance->save();
            }
            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

    }

    public function disconnect(WhatsappInstance $whatsappInstance) {
        $response = (new WhatsappIntegration($whatsappInstance))->disconnect();
        return response()->json(['status' => $response]);
    }
}
