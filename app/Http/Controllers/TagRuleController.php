<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use App\Models\User\Product;
use App\Models\User\Tag;
use App\Models\User\TagRule;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagRuleController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'product_name' => 'Produto',
            'status' => 'Status',
            'tag_name' => 'Tag',
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
                'name' => 'Configurações'
            ],
            [
                'name' => "Regras"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'product_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $tagRules = TagRule::select('tag_rules.*', 'products.product_name', 'lead_statuses.status', 'tags.tag_name')
                            ->join('products', 'tag_rules.product_id', 'products.id')
                            ->join('lead_statuses', 'tag_rules.lead_status_id', 'lead_statuses.id')
                            ->join('tags', 'tag_rules.tag_id', 'tags.id')
                            ->where('products.product_name', 'like', "%$request->searchField%")
                            ->orWhere('lead_statuses.status', 'like', "%$request->searchField%")
                            ->orWhere('tags.tag_name', 'like', "%$request->searchField%")
                            ->OrderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        } else {
            $tagRules = TagRule::select('tag_rules.*', 'products.product_name', 'lead_statuses.status', 'tags.tag_name')
                            ->join('products', 'tag_rules.product_id', 'products.id')
                            ->join('lead_statuses', 'tag_rules.lead_status_id', 'lead_statuses.id')
                            ->join('tags', 'tag_rules.tag_id', 'tags.id')
                            ->OrderBy($this->orderField, $this->orderType)
                            ->paginate($this->paginate);
        }

        return $this->getIndex('user.tag_rules.index')
                    ->withTagRules($tagRules);
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
                'link' => "/tag_rule",
                'name' => "Regras"
            ],
            [
                'name' => "Nova"
            ]
        ];

        $products = Product::orderBy('product_name', 'ASC')->get();
        $leadStatuses = LeadStatus::orderBy('status', 'ASC')->get();
        $tags = Tag::orderBy('tag_name', 'ASC')->get();


        return $this->getView('user.tag_rules.create')
                    ->withProducts($products)
                    ->withLeadStatuses($leadStatuses)
                    ->withTags($tags);
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
            'product_id' => "required",
            'lead_status_id' => "required",
            'tag_id' => "required|unique:tag_rules,tag_id,NULL,NULL,user_id,$userId,product_id,$request->product_id,lead_status_id,$request->lead_status_id",
        ]);

        $tagRule = new tagRule($request->all());
        $tagRule->active = ($request->active ?? false) ? true : false;
        return response()->json($tagRule->save());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User\TagRule  $tagRule
     * @return \Illuminate\Http\Response
     */
    public function show(TagRule $tagRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\TagRule  $tagRule
     * @return \Illuminate\Http\Response
     */
    public function edit(TagRule $tagRule)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Configurações'
            ],
            [
                'link' => "/tag_rule",
                'name' => "Regras"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        $products = Product::orderBy('product_name', 'ASC')->get();
        $leadStatuses = LeadStatus::orderBy('status', 'ASC')->get();
        $tags = Tag::orderBy('tag_name', 'ASC')->get();


        return $this->getView('user.tag_rules.edit')
                    ->withTagRule($tagRule)
                    ->withProducts($products)
                    ->withLeadStatuses($leadStatuses)
                    ->withTags($tags);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\TagRule  $tagRule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TagRule $tagRule)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $userId = Auth::user()->id;
        $this->validate($request, [
            'product_id' => "required",
            'lead_status_id' => "required",
            'tag_id' => "required|unique:tag_rules,tag_id,$tagRule->id,id,user_id,$userId,product_id,$request->product_id,lead_status_id,$request->lead_status_id",
        ]);

        $tagRule->fill($request->all());
        $tagRule->active = ($request->active ?? false) ? true : false;
        return response()->json($tagRule->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\TagRule  $tagRule
     * @return \Illuminate\Http\Response
     */
    public function destroy(TagRule $tagRule)
    {
        return response()->json($tagRule->delete());
    }
}
