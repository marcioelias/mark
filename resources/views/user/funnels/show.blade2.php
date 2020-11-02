@extends('layouts.crud.show', [
    'title' => 'Visualizar Funil',
    'route' => route('funnel.index')
])

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/organogram.css')) }}">
@endsection

@section('show-content')
<div class="tree d-flex justify-content-center">
    <ul>
        <li>
            <a href="#">{{ $funnel->funnel_description }}</a>
            <ul>
                @foreach ($funnel->steps as $step)
                <li>
                    <a href="#">{{ $step->postbackEventType->postback_event_type }}</a>
                    <ul class="vertical">
                        @foreach ($step->actions as $action)
                        <li><a href="#">{{ $action->action_description }}</a></li>
                        @endforeach
                        <li></li>
                        {{-- <li><a href="#">Finance</a></li>
                        <li><a href="#">Marketing</a></li>
                        <li><a href="#">IT/IS</a></li>
                        <li><a href="#">Service Delivery</a></li> --}}
                    </ul>
                </li>
                @endforeach
            </ul>
        </li>
    </ul>
</div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/DynamicQuillTools.js') }}"></script>
    <script src="{{ asset('js/user/funnelShow.js') }}"></script>
@endsection
