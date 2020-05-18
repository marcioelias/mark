@php
    $inputSize = isset($inputSize) ? '-'.$inputSize : '-12';
    $disabled = isset($disabled) ? $disabled : false;
    $autofocus = isset($autofocus) ? $autofocus : false;
    $required = isset($required) ? $required : false;
    $css = isset($css) ? $css : '';
    $indexSelected = isset($indexSelected) ? $indexSelected : false;
    $liveSearch = isset($liveSearch) ? $liveSearch : false;
    $defaultNone = isset($defaultNone) ? $defaultNone : false;
    $vModel = isset($vModel) ? $vModel : false;
    $div_css = isset($div_css) ? $div_css : '';
    $searchById = isset($searchById) ? $searchById : false;
@endphp
<div class="col col-sm col-md{{$inputSize}} col-lg{{$inputSize}} {{ $errors->has($field) ? ' has-error' : '' }} {{$div_css}}">
    @isset($label)
    <x-label :label="$label" :field="$field" />
    @endisset
    <select ref="{{'ref_'.$name}}" class="form-control select2 {{$css}}" {{ ($vModel) ? 'v-model='.$vModel : '' }} data-none-selected-text="{{__('strings.NothingSelected')}}" data-style="btn-secondary" {{ $liveSearch ? 'data-live-search=true' : '' }} id="{{$id}}" name="{{$name}}" {{ $required ? 'required' : '' }}  {{ $autofocus ? 'autofocus' : '' }} {{ $disabled ? 'disabled="disabled"' : '' }}>
        @if(isset($items))
            @if($defaultNone)
                <option selected value="">{{__('strings.NothingSelected')}}</option>
            @endif
            @if(is_array($items))
                @foreach($items as $key => $value)
                    <option value="{{ $key }}" {{($key==$indexSelected) ? 'selected=selected' : ''}}>{{ (($searchById) ? $key . ' - ' : '') . $value }}</option>
                @endforeach
            @else
                @foreach($items as $item)
                    @if (($item->$keyField == $indexSelected) || ($item->$keyField == old($field)))
                        <option value="{{ $item->$keyField }}" selected="selected">{{ (($searchById) ? $item->$keyField . ' - ' : '') . $item->$displayField }}</option>
                    @else
                        <option value="{{ $item->$keyField }}">{{ (($searchById) ? $item->$keyField . ' - ' : '') . $item->$displayField }}</option>
                    @endif
                @endforeach
            @endif
        @endif
    </select>

    @if ($errors->has($field))
        <span class="invalid-feedback d-block">
            <strong>{{ $errors->first($field) }}</strong>
        </span>
    @endif
</div>
