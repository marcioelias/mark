@extends('layouts.crud.vue', [
    'title' => 'Nova Ação de Marketing',
    'route' => route('marketing_action.store'),
    'redirect' => route('marketing_action.index')
])

@section('vue-component')
    <div id="marketing-action-container">
        <marketing-action />
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/DynamicQuillTools.js') }}"></script>
    <script src="{{ asset('js/user/marketingAction.js') }}"></script>
@endsection
