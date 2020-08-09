@extends('layouts.crud.vue_show', [
    'title' => 'Visualizar Funil',
    'route' => route('funnel.index')
])

@section('show-content')
    <div id="funnel-show">
        <funnel-show-component :funnel="{{ $funnel }}" />
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/DynamicQuillTools.js') }}"></script>
    <script src="{{ asset('js/user/funnelShow.js') }}"></script>
@endsection
