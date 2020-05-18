@php
    $btnAlign = isset($btnAlign) ? $btnAlign : 'Right';
    $btnColor = ['submit' => 'primary', 'reset' => 'secondary', 'button' => 'danger'];
    $fileUpload = (isset($fileUpload) && $fileUpload) ? 'enctype=multipart/form-data' : '';
    $cancelRoute = (isset($cancelRoute) ? $cancelRoute : false);
    $indexRoute = $cancelRoute ? $cancelRoute : explode('.', Route::current()->getAction()['as'])[0].'.index';
    $routeUrl .= (count(Request()->all()) > 0) ? '?'.http_build_query(Request()->all()) : '';
@endphp

<form id="crud-form" data-redirect="{{ $redirect }}" class="form" {{isset($formTarget) ? 'target='.$formTarget : ''}} role="form" method="POST" action="{{$routeUrl}}" {{$fileUpload}} data-method="{{ $method }}">
    @csrf
    @isset($method)
        @if($method != 'POST')
            @method($method)
        @endif
    @endisset

    @yield('formFields')

    <nav class="navbar navbar-expand-lg fixed-bottom pr-0">
        <div class="ml-auto">
            @if(is_array($formButtons))
                @foreach($formButtons as $formButton)
                    @if(($formButton['type'] == 'submit') || ($formButton['type'] == 'reset'))
                        <button type="{{$formButton['type']}}" class="btn btn-outline-{{$btnColor[$formButton['type']]}}" data-toggle="tooltip" data-placement="top" title="{{ __($formButton['label']) }}" data-original-title="{{ __($formButton['label']) }}">
                            @if(isset($formButton['icon']))
                                <i class="fa fa-{{$formButton['icon']}}"></i>
                            @else
                                {{ __($formButton['label']) }}
                            @endif
                        </button>
                    @else
                        <a href="{{ route($indexRoute, Request()->all() ?? []) }}" class="btn btn-outline-{{$btnColor[$formButton['type']]}}"  data-toggle="tooltip" data-placement="top" title="{{ __($formButton['label']) }}" data-original-title="{{ __($formButton['label']) }}">
                            @if(isset($formButton['icon']))
                                <i class="fa fa-{{$formButton['icon']}}"></i>
                            @else
                                {{ __($formButton['label']) }}
                            @endif
                        </a>
                    @endif
                @endforeach
            @else
                <button type="submit" class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="{{ __($formButton['label']) }}" data-original-title="{{ __($formButton['label']) }}">
                    @if(isset($formButton['icon']))
                        <span class="glyphicon glyphicon-{{$formButton['icon']}}"></span>
                    @else
                        {{ __($formButton['label']) }}
                    @endif
                </button>
            @endif
        </div>
    </nav>
</form>

@push('document-ready')
    $('#crud-form').on('submit', async function(e) {
        e.preventDefault();
        const formMethod = $('#crud-form').attr('data-method')
        const formAction = $('#crud-form').attr('action')
        const formRedirect = $('#crud-form').attr('data-redirect')
        try {
            await $.ajax({
                url: formAction,
                type: formMethod,
                dataType: 'json',
                data: $('#crud-form').serialize(),
                success: function(data) {
                    Swal.fire({
                        title: 'Concluído',
                        text: formMethod === 'POST' ? 'Registro incluído com sucesso!' : 'Registro alterado com sucesso!',
                        icon: 'success'
                    }).then(function() {
                        window.location.replace(formRedirect)
                    })
                }
            })
        } catch (err) {
            Swal.fire({
                title: 'Oops...',
                text: 'Algo deu errado!',
                icon: 'error'
            }).then(function() {
                const errors = err.responseJSON.errors
                $.each(errors, function(k, v) {
                    $('#'+k).addClass('is-invalid')
                    $('#error-'+k).html(v)
                })
            })
        }
    })
@endpush
