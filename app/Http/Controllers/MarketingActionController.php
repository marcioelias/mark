<?php

namespace App\Http\Controllers;

use App\Constants\MarketingActionStatuses;
use App\Models\User\Customer;
use App\Models\User\Funnel;
use App\Models\User\MarketingAction;
use App\Models\User\Product;
use App\Rules\MessagesAvailable;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketingActionController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'marketing_action_description' => 'Descrição',
            'product_name' => 'Produto',
            'start_at' => ['label' => 'Dt. Execução', 'type' => 'datetime'],
            'marketing_action_status' => 'Status'
            /* 'active' => ['label' => 'Ativo', 'type' => 'bool'] */
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
                'name' => 'Leads'
            ],
            [
                'name' => "Remarketing"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'marketing_action_description',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $marketingActions = MarketingAction::select(['marketing_actions.*', 'products.product_name', 'marketing_action_statuses.marketing_action_status'])
                            ->join('products', 'products.id', 'marketing_actions.product_id')
                            ->join('marketing_action_statuses', 'marketing_action_statuses.id', 'marketing_actions.marketing_action_status_id')
                            ->where('marketing_actions.marketing_action_description', 'like', "%$request->searchField%")
                            ->orWhere('products.product_name', 'like', "%$request->searchField%")
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $marketingActions = MarketingAction::select(['marketing_actions.*', 'products.product_name', 'marketing_action_statuses.marketing_action_status'])
                            ->join('products', 'products.id', 'marketing_actions.product_id')
                            ->join('marketing_action_statuses', 'marketing_action_statuses.id', 'marketing_actions.marketing_action_status_id')
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.marketing_actions.index')
                    ->withMarketingActions($marketingActions);
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
                'name' => 'Leads'
            ],
            [
                'link' => "/marketing_action",
                'name' => "Ação de Marketing"
            ],
            [
                'name' => "Nova"
            ]
        ];

        return $this->getView('user.marketing_actions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $userId = Auth::user()->id;
        $this->validate($request, [
                'description' => "required|unique:marketing_actions,marketing_action_description,NULL,NULL,user_id,$userId",
                'product_id' => 'required',
                'action_type_id' => 'required',
                'message' => ['required', new MessagesAvailable($request->action_type_id ?? '', count($request->customers ?? 0)) ],
                'customers' => 'min:1'
            ], [
                'description.unique' => 'Já existe uma Ação de Marketing com essa mesma descrição.',
                'action_type_id.required' => 'Nenhuma mensagem foi cadastrada para envio.',
                'message.required' => 'Dados da mensagem não informados.',
                'customers.min' => 'Nenhum cliente selecionado.'
            ], [
                'description' => 'Descrição',
                'product_id' => 'Produto'
            ]);

        DB::beginTransaction();
        try {
            $startTime = explode(':', $request->start_time);
            Log::debug($request->all());
            $startAt = Carbon::parse($request->start_date)->setTime($startTime[0], $startTime[1])->toDateTimeString();

            $images = Arr::get($request->message, 'options.images');
            if ($images) {
                $marketingAction = MarketingAction::create([
                    'marketing_action_description' => $request->description,
                    'product_id' => $request->product_id,
                    'action_type_id' => $request->action_type_id,
                    'marketing_action_status_id' => MarketingActionStatuses::PENDING,
                    'action_message' => [],
                    'start_at' => $startAt
                ]);
                $message = $request->message;
                foreach ($images as $image) {
                    $marketingAction->addMediaFromBase64($image)->toMediaCollection('mail-images');
                    $mediaItems = $marketingAction->load('media')->getMedia('mail-images');
                    $message['data'] = str_replace($image, $mediaItems[count($mediaItems) - 1]->getFullUrl(), $message['data']);
                }
                $message['options']['images'] = [];
                $marketingAction->action_message = $message;
            } else {
                $marketingAction = new MarketingAction([
                    'marketing_action_description' => $request->description,
                    'product_id' => $request->product_id,
                    'action_type_id' => $request->action_type_id,
                    'marketing_action_status_id' => MarketingActionStatuses::PENDING,
                    'action_message' => $request->message,
                    'start_at' => $startAt
                ]);
            }

            if ($marketingAction->save()) {
                try {
                    $customerActions = array_map(fn($id) => $id = ['schedule_date' => $startAt], array_flip($request->customers));
                    $marketingAction->customers()->sync($customerActions);

                    DB::commit();

                    return response()->json(['redirect' => route('marketing_action.index')]);
                } catch (Exception $e) {
                    Log::emergency($e);
                }
            } else {
                throw new Exception('Ocorreu um erro ao salvar a Ação de Marketing');
            }


        } catch (Exception $e) {
            DB::rollBack();
            Log::emergency($e->getMessage());
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\App\User\MarketingAction  $marketingAction
     * @return \Illuminate\Http\Response
     */
    public function show(MarketingAction $marketingAction)
    {
        return $this->getView('user.marketing_actions.show')
                    ->withMarketingAction($marketingAction->load('customers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\App\User\MarketingAction  $marketingAction
     * @return \Illuminate\Http\Response
     */
    public function edit(MarketingAction $marketingAction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\App\User\MarketingAction  $marketingAction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MarketingAction $marketingAction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\App\User\MarketingAction  $marketingAction
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarketingAction $marketingAction)
    {
        try {
            if ($marketingAction->marketing_action_status_id === MarketingActionStatuses::PENDING) {
                return response()->json($marketingAction->delete());
            } else {
                throw new Exception('Não é possível remover Remarketing com status diferente de Pendente.');
            }
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 403);
        }
    }
}
