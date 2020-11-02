<?php

namespace App\Http\Controllers;

use App\Models\Plataform;
use App\Models\User\PlataformConfig;
use App\Traits\LayoutConfigTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlataformConfigController extends Controller
{
    use LayoutConfigTrait;

    public $breadcrumbs;

    public function fields() {
        return array(
            'plataform_name' => 'Plataforma',
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
                'name' => "Plataformas"
            ],
        ];

        if ($request->searchField) {
            $plataformConfigs = PlataformConfig::select(['plataform_configs.*', 'plataforms.plataform_name'])
                                    ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                                    ->where('plataform_name', 'like', "%$request->searchField%")
                                    ->orderBy($this->orderField, $this->orderType)
                                    ->paginate($this->paginate);
        } else {
            $plataformConfigs = PlataformConfig::select(['plataform_configs.*', 'plataforms.plataform_name'])
                                    ->join('plataforms', 'plataforms.id', 'plataform_configs.plataform_id')
                                    ->orderBy($this->orderField, $this->orderType)
                                    ->paginate($this->paginate);
        }

        return $this->getIndex('user.plataform_configs.index')
                    ->withPlataformConfigs($plataformConfigs);
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
                'link' => "/plataform_config",
                'name' => "Plataformas"
            ],
            [
                'name' => "Nova"
            ]
        ];

        $user_id = Auth::user()->id;
        $plataforms = Plataform::whereDoesntHave('plataformConfigs', function(Builder $query) use ($user_id) {
                            $query->where('user_id', $user_id);
                        })
                        ->orderBy('plataform_name', 'ASC')
                        ->get();
        return $this->getView('user.plataform_configs.create')
                    ->withPlataforms($plataforms);
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
            'plataform_id' => "required",
            'plataform_key' => 'required'
        ]);

        $plataformConfig = new PlataformConfig($request->all());
        $plataformConfig->active = ($request->active ?? false) ? true : false;
        return response()->json($plataformConfig->save());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User\PlataformConfig  $plataformConfig
     * @return \Illuminate\Http\Response
     */
    public function edit(PlataformConfig $plataformConfig)
    {
        $this->breadcrumbs = [
            [
                'name' => 'Configurações'
            ],
            [
                'link' => "/plataform_config",
                'name' => "Plataformas"
            ],
            [
                'name' => "Alterar"
            ]
        ];

        $user_id = Auth::user()->id;
        $plataforms = Plataform::whereDoesntHave('plataformConfigs', function(Builder $query) use ($user_id) {
                            $query->where('user_id', $user_id);
                        })
                        ->orWhere('id', $plataformConfig->plataform_id)
                        ->orderBy('plataform_name', 'ASC')
                        ->get();
        return $this->getView('user.plataform_configs.edit')
                    ->withPlataformConfig($plataformConfig)
                    ->withPlataforms($plataforms);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User\PlataformConfig  $plataformConfig
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlataformConfig $plataformConfig)
    {
        $this->validate($request, [
            'plataform_id' => "required",
            'plataform_key' => 'required'
        ]);

        $plataformConfig->fill($request->all());
        $plataformConfig->active = ($request->active ?? false) ? true : false;
        return response()->json($plataformConfig->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User\PlataformConfig  $plataformConfig
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlataformConfig $plataformConfig)
    {
        //
    }

    public function getWebhookUrl(PlataformConfig $plataformConfig) {
        return response()->json(['url' => route('webhook.url', ['plataformConfig' => $plataformConfig])]);
    }
}
