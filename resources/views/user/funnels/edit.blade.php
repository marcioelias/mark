@extends('layouts.crud.vue', [
    'title' => 'Alterar Funil',
    'route' => route('funnel.store'),
    'redirect' => route('funnel.index')
])

@section('vue-component')
    <div id="funnel">
        <funnel-component :funnel-id="'{{ $funnel->id }}'" />
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/DynamicQuillTools.js') }}"></script>
    <script src="{{ asset('js/user/funnel.js') }}"></script>
@endsection
