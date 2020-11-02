@php
    $inputSize = isset($inputSize) ? '-'.$inputSize : '-1';
    $disabled = isset($disabled) ? $disabled : false;
    $autofocus = isset($autofocus) ? $autofocus : false;
    $required = isset($required) ? $required : false;
    $css = isset($css) ? $css : '';
    $name = isset($name) ? $name : $field;
    $id = isset($id) ? $id : $name;
    $label = isset($label) ? $label : $name;
    $dataOn = isset($dataOn) ? $dataOn : 'Sim';
    $dataOff = isset($dataOff) ? $dataOff : 'Não';
    $dataWidth = isset($dataWidth) ? $dataWidth : 55;
    $dataSize = isset($dataSize) ? $dataSize : 'mini';
    $dataOnStyle = isset($dataOnStyle) ? $dataOnStyle : 'success';
    $dataOffStyle = isset($dataOffStyle) ? $dataOffStyle : 'secondary';
    $checked = (isset($checked) ? (($checked) || (old($field))) : old($field)) ? 'checked' : '';
    $permission = isset($permission) ? $permission : true;
@endphp

<div class="col col-sm col-md{{$inputSize}} col-lg{{$inputSize}}">
    @if ($label)
    <label>{{ $label }}</label>
    @endif
    <div class="custom-control custom-switch switch-md custom-switch-{{ $disabled ? 'secondary' : 'primary' }} mr-2 mb-1" style="margin-top: 0.6rem; {{ $css }}">
        <input type="checkbox" name="{{ $name }}" class="custom-control-input" id="switch-{{ $name }}" {{ $checked }} {{ $disabled ? 'disabled' : '' }}>
        <label class="custom-control-label" for="switch-{{ $name }}">
            <span class="switch-icon-left"><i class="feather icon-check"></i></span>
            <span class="switch-icon-right"><i class="feather icon-check"></i></span>
        </label>
    </div>
</div>
