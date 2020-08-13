<?php

namespace App\Http\Controllers;

use App\Models\User\WhatsappTemplate;
use App\Models\Variable;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappTemplateController extends Controller
{

    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'template_name' => 'Template'
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
                'name' => 'Whatsapp 2.0'
            ],
            [
                'name' => "Modelos de Msg"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'template_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $whatsappTemplates = WhatsappTemplate::where('template_name', 'like', "%$request->searchField%")
                                            ->OrderBy($this->orderField, $this->orderType)
                                            ->paginate($this->paginate);
        } else {
            $whatsappTemplates = WhatsappTemplate::OrderBy($this->orderField, $this->orderType)
                                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.whatsapp_templates.index')
                    ->withWhatsappTemplates($whatsappTemplates);
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
                'name' => 'Whatsapp 2.0'
            ],
            [
                'link' => "/whatsapp_template",
                'name' => "Template de Msg"
            ],
            [
                'name' => "Novo"
            ]
        ];

        $variables = Variable::orderBy('description', 'ASC')->get();

        return $this->getView('user.whatsapp_templates.create')
                    ->withVariables($variables);
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
            'template_name' => "required|unique:tags,tag_name,NULL,NULL,user_id,$userId",
        ]);

        $whatsappTemplate = new WhatsappTemplate($request->all());
        return response()->json($whatsappTemplate->save());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User\WhatsappTemplate  $whatsappTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(WhatsappTemplate $whatsappTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\WhatsappTemplate  $whatsappTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(WhatsappTemplate $whatsappTemplate)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Whatsapp 2.0'
            ],
            [
                'link' => "/whatsapp_template",
                'name' => "Template de Msg"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        $variables = Variable::orderBy('description', 'ASC')->get();

        return $this->getView('user.whatsapp_templates.edit')
                    ->withWhatsappTemplate($whatsappTemplate)
                    ->withVariables($variables);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\WhatsappTemplate  $whatsappTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WhatsappTemplate $whatsappTemplate)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $userId = Auth::user()->id;
        $this->validate($request, [
            'template_name' => "required|unique:tags,tag_name,$whatsappTemplate->id,id,user_id,$userId",
        ]);

        $whatsappTemplate = $whatsappTemplate->fill($request->all());
        return response()->json($whatsappTemplate->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\WhatsappTemplate  $whatsappTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhatsappTemplate $whatsappTemplate)
    {
        return response()->json($whatsappTemplate->delete());
    }
}
