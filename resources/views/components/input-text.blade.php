@php
    $inputSize = isset($inputSize) ? '-'.$inputSize : '-12';
    $items = isset($items) ? $items : false;
    $disabled = isset($disabled) ? $disabled : false;
    $autofocus = isset($autofocus) ? $autofocus : false;
    $required = isset($required) ? $required : false;
    $css = isset($css) ? $css : '';
    $div_css = isset($div_css) ? $div_css : '';
    $vModel = isset($vModel) ? $vModel : false;
    $readOnly = isset($readOnly) ? $readOnly : false;
@endphp

<div class="col col-sm col-md{{$inputSize}} col-lg{{$inputSize}} {{$div_css}}">
    @if($icon)
    @isset($label)
    <x-label :label="$label" :field="$field" />
    @endisset
    <fieldset class="position-relative has-icon-{{ $icon['side'] }} {{ $icon['divider'] ? 'input-divider-left' : '' }}">
        <input type="text" name="{{ $field }}" class="form-control {{ $css }}" id="{{$id}}" value="{{ isset($inputValue) ? $inputValue : old($field) }}" placeholder="{{ isset($label) ? $label : '' }}" {{ $required ? 'required' : '' }}  {{ $autofocus ? 'autofocus' : '' }} {{ $disabled ? 'disabled="disabled"' : '' }} {{ $readOnly ? 'readonly' : '' }}>
        <div class="form-control-position">
            <i class="feather icon-{{ $icon['type'] }}"></i>
        </div>
    </fieldset>
    @else
    @isset($label)
    <x-label :label="$label" :field="$field" />
    @endisset
    <input type="text" name="{{ $field }}" class="form-control {{$css}}" id="{{$id}}" value="{{ isset($inputValue) ? $inputValue : old($field) }}" step="any" placeholder="{{ isset($label) ? $label : '' }}" {{ $required ? 'required' : '' }}  {{ $autofocus ? 'autofocus' : '' }} {{ $disabled ? 'disabled="disabled"' : '' }} {{ $readOnly ? 'readonly' : '' }}>
    @endif
    <div class="invalid-feedback" id="error-{{ $id }}"></div>
</div>
