@extends('layouts.crud.vue_show', [
    'title' => 'Visualizar Funil',
    'route' => route('funnel.index')
])

@section('show-content')
    <div id="funnel-show">
        <funnel-org-chart :funnel="{{ $funnel }}" />
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/user/funnelOrgChart.js') }}"></script>
@endsection
