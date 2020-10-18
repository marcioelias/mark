<?php

namespace App\Http\Controllers;

use App\Models\User\LeadStatus;
use App\Traits\LayoutConfigTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LeadStatusController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'status' => 'Status',
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
                'name' => "Status"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'status',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $leadStatuses = LeadStatus::where('status', 'like', "%$request->searchField%")
                                ->orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        } else {
            $leadStatuses = LeadStatus::orderBy($this->orderField, $this->orderType)
                                ->paginate($this->paginate);
        }

        if (isset($request->accessDenied)) {
            return $this->getIndex('user.lead_statuses.index')
                        ->withLeadStatuses($leadStatuses)
                        ->withAccessDenied(true);
        } else {
            return $this->getIndex('user.lead_statuses.index')
                        ->withLeadStatuses($leadStatuses);
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
                'link' => "/lead_status",
                'name' => "Status"
            ],
            [
                'name' => "Novo"
            ]
        ];

        return View('user.lead_statuses.create');
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
            'status' => "required|unique:lead_statuses,status,NULL,NULL,user_id,$userId,user_id,NULL",
        ]);

        $leadStatus = new LeadStatus($request->all());
        $leadStatus->save();

        return response()->json($leadStatus);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LeadStatus  $leadStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(LeadStatus $leadStatus)
    {
        /* não permite edição em status padrão do sistema */
        if (!$leadStatus->user_id) {
            return Redirect::route('lead_status.index', ['accessDenied' => true]);
        }
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/lead_status",
                'name' => "Status"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        return View('user.lead_statuses.edit')
                ->withLeadStatus($leadStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LeadStatus  $leadStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeadStatus $leadStatus)
    {
        $userId = Auth::user()->id;
        $this->validate($request, [
            'status' => "required|unique:lead_statuses,status,$leadStatus->id,id,user_id,$userId,user_id,NULL"
        ]);

        $leadStatus->fill($request->all());
        $leadStatus->save();

        return response()->json($leadStatus);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LeadStatus  $leadStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeadStatus $leadStatus)
    {
        /* não permite exclusão em status padrão do sistema */
        if (!$leadStatus->user_id) {
            throw new Exception('Não é possível excluir um Status padrão do sistema.');
        }
        return response()->json($leadStatus->delete());
    }

    public function getLeadStatusesJson() {
        return response()->json(LeadStatus::orderBy('status', 'ASC')->get());
    }
}
