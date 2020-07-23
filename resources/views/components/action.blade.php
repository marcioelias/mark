<?php
    switch($action) {
        case 'show':
            $btn_style = 'btn-success icon-btn-sm-padding';
            $btn_icon = 'eye';
            $tooltip = 'Visualizar';
            //$permission = 'listar-'.str_replace('_', '-', $model);
            break;
        case 'edit':
            $btn_style = 'btn-primary icon-btn-sm-padding';
            $btn_icon = 'pen';
            $tooltip = 'Editar';
            //$permission = 'alterar-'.str_replace('_', '-', $model);
            break;
        case 'destroy':
            $btn_style = 'btn-danger icon-btn-sm-padding deleteBtn';
            $btn_icon = 'minus-cirle';
            $tooltip = 'Remover';
            //$permission = 'excluir-'.str_replace('_', '-', $model);
            break;
    }
    $target = isset($target) ? 'target='.$target : '';
?>
@php
    $displayField = isset($displayField) ? $displayField : 'name';
    $keyField = isset($keyField) ? $keyField : 'id';
    $parameters = Request()->request->all() ?? [];
@endphp

@if($action == 'destroy')
    <form id="deleteForm-{{$row->id}}" action="{{route($model.'.'.$action, ["$model" => $row->$keyField])}}" method="POST" style="display: inline">
        <input type="hidden" name="backUrlParams" value="{{ json_encode(Request()->request->all()) }}">
        <span data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" data-original-title="{{$tooltip}}">
             <button class="btn btn-sm {{$btn_style}}" data-route="{{route($model.'.'.$action, ["$model" => $row->$keyField])}}" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="{{__('Remover ').__('models.'.$model)}}"
                data-message="Remover {{ __('models.'.$model).': '.$row->$displayField}}?">
                <i class="fa fa-minus-circle" style="font-size: 1.2rem"></i>
            </button>
        </span>
        <input type="hidden" name="_method" value="DELETE">
        @csrf
    </form>
@else
    <span data-toggle="tooltip" data-placement="top" title="{{$tooltip}}" data-original-title="{{$tooltip}}">
        <a href="{{route($model.'.'.$action, Arr::add($parameters, $model, $row->$keyField))}}" {{ $target }} class="btn btn-sm {{$btn_style}}"><i class="fa fa-{{$btn_icon}}" style="font-size: 1.2rem"></i></a>
    </span>
@endif
