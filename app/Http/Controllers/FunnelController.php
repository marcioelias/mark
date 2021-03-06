<?php

namespace App\Http\Controllers;

use App\Constants\ScheduleStatus;
use App\Models\User\Funnel;
use App\Models\User\FunnelStep;
use App\Models\User\FunnelStepAction;
use App\Models\User\FunnelStepLead;
use App\Models\User\Product;
use App\Models\User\Schedule;
use App\Traits\LayoutConfigTrait;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use RecursiveArrayIterator;

class FunnelController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'funnel_description' => 'Descrição',
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
                'name' => 'Cadastros'
            ],
            [
                'name' => "Funis"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'funnel_description',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $funnels = Funnel::where('funnels.description', 'like', "%$request->searchField%")
                            ->orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $funnels = Funnel::orderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.funnels.index')
                    ->withFunnels($funnels);
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
                'link' => "/funnel",
                'name' => "Funis"
            ],
            [
                'name' => "Novo"
            ]
        ];

        /* $products = Product::doesntHave('funnel')
                            ->select('products.id', 'products.product_name')
                            ->where('produts.active', true)
                            ->orderBy('products.product_name', 'asc')
                            ->get(); */

        return $this->getView('user.funnels.create');
                    //->withProducts($products);
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
        $this->validate($request, [
            'description' => "required",
        ], [],
        [
            'description' => 'Descrição',
        ]);

        DB::beginTransaction();

        try {
            $funnel = new Funnel([
                'funnel_description' => $request->description,
                'is_sales_funnel' => $request->is_sales_funnel,
                'active' => $request->active
            ]);

            if ($funnel->save()) {
                /* se ocorreu tudo bem ao salvar o funil, inclui os passos */
                foreach ($request->steps as $step) {
                    $newStep = new FunnelStep([
                        //'funnel_step_sequence' => $step['funnel_step_sequence'],
                        'postback_event_type_id' => $step['postback_event_type_id'],
                    ]);
                    try {
                        $funnel->steps()->save($newStep);
                    } catch (Exception $e) {
                        throw $e;
                    }

                    foreach ($step['actions'] as $action) {
                        $jsonData = $action['action_data'];
                        $images = Arr::get($action['action_data'], 'options.images');
                        if ($images) {
                            $newStepAction = $newStep->actions()->create([
                                'action_type_id' => $action['action_type_id'],
                                'action_sequence' => $action['action_sequence'],
                                'seconds_after' => $this->daysMinutesToSeconds($jsonData),
                                'action_description' => $action['action_description'],
                                'action_data' => [],
                            ]);

                            foreach ($images as $image) {
                                $newStepAction->addMediaFromBase64($image)->toMediaCollection('mail-images');
                                $mediaItems = $newStepAction->load('media')->getMedia('mail-images');
                                $jsonData['data'] = str_replace($image, $mediaItems[count($mediaItems) - 1]->getFullUrl(), $jsonData['data']);
                            }
                            $jsonData['options']['images'] = [];

                            $newStepAction->action_data = $jsonData;

                            $newStepAction->save();
                        } else {
                            $newStepAction = new FunnelStepAction([
                                'action_type_id' => $action['action_type_id'],
                                'action_sequence' => $action['action_sequence'],
                                'seconds_after' => $this->daysMinutesToSeconds($jsonData),
                                'action_description' => $action['action_description'],
                                'action_data' => $action['action_data'],
                            ]);

                            try {
                                $newStep->actions()->save($newStepAction);
                            } catch (Exception $e) {
                                throw $e;
                            }
                        }
                    }
                }

                DB::commit();

                return response()->json(['redirect' => route('funnel.index')]);

            } else {
                throw new \Exception('Ocorreu um erro ao salvar o Funil.');
            }

        } catch (\Exception $e) {
            Log::emergency($e->getMessage());
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function show(Funnel $funnel)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/funnel",
                'name' => "Funis"
            ],
            [
                'name' => "Visualizar Funil"
            ]
        ];

        $res = [
            'id' => $funnel->id,
            //'name' => 'Funil',
            'description' => $funnel->funnel_description,
        ];

        foreach ($funnel->steps as $step) {
            $stepRes = [
                'id' => $step->id,
                //'name' => 'Passo',
                'description' => $step->postbackEventType->postback_event_type
            ];
            foreach ($step->actions as $action) {
                $stepRes['children'][] = [
                    'id' => $action->id,
                    //'name' => 'Ação',
                    'description' => $action->action_description
                ];
            }
            $res['children'][] = $stepRes;
        }

        //return response()->json($res);

        return $this->getView('user.funnels.show')
                    ->withFunnel($funnel->load(['steps.actions']));
    }

    public function showJson(Funnel $funnel) {
        $res = [
            'id' => $funnel->id,
            //'name' => 'Funil',
            'description' => $funnel->funnel_description,
        ];
        foreach ($funnel->steps as $step) {
            $stepRes = [
                'id' => $step->id,
                //'name' => 'Passo',
                'description' => $step->postbackEventType->postback_event_type
            ];
            foreach ($step->actions as $action) {
                $stepRes['children'][] = [
                    'id' => $action->id,
                    //'name' => 'Ação',
                    'description' => $action->action_description
                ];
            }
            $res['children'][] = $stepRes;
        }

        return response()->json($res);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function edit(Funnel $funnel)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/funnel",
                'name' => "Funis"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        return $this->getView('user.funnels.edit')
                    ->withFunnel($funnel);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Funnel $funnel)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'description' => "required",
        ], [],
        [
            'description' => 'Descrição',
        ]);

        DB::beginTransaction();

        try {
            $funnel->fill([
                'funnel_description' => $request->description,
                'is_sales_funnel' => $request->is_sales_funnel,
                'active' => $request->active
            ]);

            if ($funnel->save()) {
                /* se ocorreu tudo bem ao salvar o funil, inclui os passos */
                foreach ($request->steps as $step) {
                    $newStep = FunnelStep::updateOrCreate(
                        [
                            'id' => $step['id'] ?? null
                        ],
                        [
                            'funnel_id' => $funnel->id,
                            'postback_event_type_id' => $step['postback_event_type_id'],
                        ]
                    );

                    foreach ($step['actions'] as $action) {
                        if ($action['deleted']) {
                            FunnelStepAction::find($action['id'])->delete();
                        } else {
                            $jsonData = $action['action_data'];
                            $images = Arr::get($action['action_data'], 'options.images');
                            if ($images) {
                                $newStepAction = FunnelStepAction::updateOrCreate(
                                    ['id' => $action['id']],
                                    [
                                        'funnel_step_id' => $newStep->id,
                                        'action_type_id' => $action['action_type_id'],
                                        'action_sequence' => $action['action_sequence'],
                                        'seconds_after' => $this->daysMinutesToSeconds($jsonData),
                                        'action_description' => $action['action_description'],
                                        'action_data' => [],
                                        'deleted' => $action['deleted']
                                    ]
                                );

                                foreach ($images as $image) {
                                    $newStepAction->addMediaFromBase64($image)->toMediaCollection('mail-images');
                                    $mediaItems = $newStepAction->load('media')->getMedia('mail-images');
                                    $jsonData['data'] = str_replace($image, $mediaItems[count($mediaItems) - 1]->getFullUrl(), $jsonData['data']);
                                }

                                $jsonData['options']['images'] = [];

                                $newStepAction->action_data = $jsonData;

                                $newStepAction->save();

                                $schedules = $newStepAction->schedule->where('shedule_status_id', ScheduleStatus::PENDING);

                                foreach ($schedules as $schedule) {
                                    try {
                                        $funnelStepLead = FunnelStepLead::where('lead_id', $schedule->lead_id)
                                                                        ->where('funnel_step_id', $schedule->funnel_step_id)
                                                                        ->first();

                                        if ($funnelStepLead) {
                                            $schedule->start_at = Carbon::parse($funnelStepLead->created_at)->startOfDay()
                                                                                                            ->addDays($newStepAction->action_data['options']['days_after'] ?? 0);
                                            $schedule->start_period = Carbon::parse($newStepAction->action_data['options']['start_time'] ?? '00:00')->toTimeString();
                                            $schedule->end_period = Carbon::parse($newStepAction->action_data['options']['end_time'] ?? '23:59')->toTimeString();
                                            $schedule->delay_before_start = $newStepAction->action_data['options']['delay_minutes'] ?? 0;
                                            $schedule->save();
                                        }
                                    } catch (Exception $e) {
                                        Log::alert('Schedule: '.$schedule->id.' => '.$e->getMessage());
                                    }
                                }
                            } else {
                                $newStepAction = FunnelStepAction::updateOrCreate(
                                    ['id' => $action['id']],
                                    [
                                        'funnel_step_id' => $newStep->id,
                                        'action_type_id' => $action['action_type_id'],
                                        'action_sequence' => $action['action_sequence'],
                                        'seconds_after' => $this->daysMinutesToSeconds($jsonData),
                                        'action_description' => $action['action_description'],
                                        'action_data' => $action['action_data'],
                                        'deleted' => $action['deleted']
                                    ]
                                );

                                $schedules = $newStepAction->schedule->where('shedule_status_id', ScheduleStatus::PENDING);

                                foreach ($schedules as $schedule) {
                                    try {
                                        $funnelStepLead = FunnelStepLead::where('lead_id', $schedule->lead_id)
                                                                        ->where('funnel_step_id', $schedule->funnel_step_id)
                                                                        ->first();

                                        if ($funnelStepLead) {
                                            $schedule->start_at = Carbon::parse($funnelStepLead->created_at)->startOfDay()
                                                                                                            ->addDays($newStepAction->action_data['options']['days_after'] ?? 0);
                                            $schedule->start_period = Carbon::parse($newStepAction->action_data['options']['start_time'] ?? '00:00')->toTimeString();
                                            $schedule->end_period = Carbon::parse($newStepAction->action_data['options']['end_time'] ?? '23:59')->toTimeString();
                                            $schedule->delay_before_start = $newStepAction->action_data['options']['delay_minutes'] ?? 0;
                                            $schedule->save();
                                        }
                                    } catch (Exception $e) {
                                        Log::alert('Schedule: '.$schedule->id.' => '.$e->getMessage());
                                    }
                                }
                            }
                        }
                    }
                }

                DB::commit();

                return response()->json(['redirect' => route('funnel.index')]);

            } else {
                throw new \Exception('Ocorreu um erro ao salvar o Funil.');
            }

        } catch (\Exception $e) {
            Log::emergency($e);
            DB::rollBack();
            return response()->json($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Funnel  $funnel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Funnel $funnel)
    {
        return response()->json($funnel->delete());
    }

    public function getFunnelJson(Funnel $funnel) {
        return response()->json($funnel->load('steps.actions'));
    }

    public function getRemarketingFunnelsJson() {
        return response()->json(Funnel::RemarketingFunnel()
                                    ->Active()
                                    ->orderBy('funnel_description', 'ASC')
                                    ->get());
    }

    public function daysMinutesToSeconds(array $data) {
        $days = ($data['options']['days_after'] ?? 0) * 86400;
        $minutes = ($data['options']['delay_minutes'] ?? 0) * 60;

        return $days+$minutes;
    }
}
