<?php

namespace App\Http\Controllers;

use App\Models\User\Product;
use App\Models\User\Tag;
use App\Traits\LayoutConfigTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'product_name' => 'Produto',
            'tag_name' => 'Tag'
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
                'name' => "Tags"
            ],
        ];

        $this->setOrder($request, [
            'order_by' => 'product_name',
            'order_type' => 'ASC'
        ]);

        if ($request->searchField) {
            $tags = Tag::select('tags.*', 'products.product_name')
                        ->join('products', 'products.id', 'tags.product_id')
                        ->whereRaw("((tags.product_id = ?) || (? is null))", [$request->product_id, $request->product_id])
                        ->where('tag_name', 'like', "%$request->searchField%")
                        ->OrderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        } else {
            $tags = Tag::select('tags.*', 'products.product_name')
                        ->join('products', 'products.id', 'tags.product_id')
                        ->whereRaw("((tags.product_id = ?) || (? is null))", [$request->product_id, $request->product_id])
                        ->OrderBy($this->orderField, $this->orderType)
                        ->paginate($this->paginate);
        }

        return $this->getIndex('user.tags.index')
                    ->withTags($tags);
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
                'link' => "/tag",
                'name' => "Tags"
            ],
            [
                'name' => "Nova"
            ]
        ];
        $products = Product::Active()
                        ->orderBy('product_name')
                        ->get();

        return $this->getView('user.tags.create')->withProducts($products);
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
            'tag_name' => "required|unique:tags,tag_name,NULL,NULL,user_id,$userId",
            'product_id' => 'required'
        ]);

        $tag = new Tag($request->all());
        return response()->json($tag->save());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Cadastros'
            ],
            [
                'link' => "/tag",
                'name' => "Tags"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        $products = Product::Active()
                        ->Where('id', $tag->product_id)
                        ->orderBy('product_name', 'asc')
                        ->get();

        return $this->getView('user.tags.edit')->withTag($tag)->withProducts($products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //unique:table[,column[,ignore value[,ignore column[,where column,where value]...]]]
        $this->validate($request, [
            'tag_name' => "required|unique:tags,tag_name,$tag->id,id,user_id,$tag->user_id",
        ]);

        $tag->fill($request->all());
        return response()->json($tag->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\Tag $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        return response()->json($tag->delete());
    }

    /**
     * Get all tags in json Format
     *
     * @return \Illuminate\Http\Response
     */
    public function getTagsJson() {
        $tags = Tag::orderBy('tag_name', 'asc')->get();
        return response()->json($tags);
    }
}
