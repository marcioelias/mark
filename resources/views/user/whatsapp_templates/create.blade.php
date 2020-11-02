@extends('layouts.crud.create', [
    'title' => 'Novo Template de Msg',
    'route' => route('whatsapp_template.store'),
    'redirect' => route('whatsapp_template.index')
])

@section('create-form')
    <div class="form-group">
        <div class="row">
            @component('components.input-text', [
                    'field' => 'template_name',
                    'label' => 'Template',
                    'inputSize' => 10
                ])
            @endcomponent
            <div class="col-md-2 d-flex align-items-end justify-content-end mt-2">
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Variáveis
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        @foreach ($variables as $variable)
                        <a class="dropdown-item dw-variables" data-variable="{{ $variable->variable }}">{{ $variable->description }}</a>
                        @endforeach
                    </div>
                  </div>
            </div>
        </div>
    </div>
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'textarea',
                'field' => 'template_content',
                'label' => 'Conteúdo da Mensagem',
                'required' => true,
                'inputSize' => 12
            ]
        ]
    ])
    @endcomponent
@endsection

@push('custom-script')
    <script>
        $('.dw-variables').on('click', (event) => {
            let variable = event.currentTarget
            insertAtCaret('template_content', variable.getAttribute("data-variable"))
            //console.log(variable.getAttribute("data-variable"))
        })

        function insertAtCaret(areaId,text) {
            var txtarea = document.getElementById(areaId);
            var scrollPos = txtarea.scrollTop;
            var strPos = 0;
            var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
                "ff" : (document.selection ? "ie" : false ) );
            if (br == "ie") {
                txtarea.focus();
                var range = document.selection.createRange();
                range.moveStart ('character', -txtarea.value.length);
                strPos = range.text.length;
            }
            else if (br == "ff") strPos = txtarea.selectionStart;

            var front = (txtarea.value).substring(0,strPos);
            var back = (txtarea.value).substring(strPos,txtarea.value.length);
            txtarea.value=front+text+back;
            strPos = strPos + text.length;
            if (br == "ie") {
                txtarea.focus();
                var range = document.selection.createRange();
                range.moveStart ('character', -txtarea.value.length);
                range.moveStart ('character', strPos);
                range.moveEnd ('character', 0);
                range.select();
            }
            else if (br == "ff") {
                txtarea.selectionStart = strPos;
                txtarea.selectionEnd = strPos;
                txtarea.focus();
            }
            txtarea.scrollTop = scrollPos;
        }
    </script>
@endpush
