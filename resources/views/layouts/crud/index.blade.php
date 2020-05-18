@extends('layouts.contentLayoutMaster')

@section('title', $tableConf['tableTitle'])

@section('content')
    @component('components.table', $tableConf);
    @endcomponent
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
@endsection
