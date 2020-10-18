@extends('layouts.contentLayoutMaster')

@section('title', $tableConf['tableTitle'])

@section('content')
    @component('components.table', $tableConf);
    @endcomponent
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
    @stack('custom-scripts')
@endsection

@push('document-ready')
$('select:not(.swal2-select)').select2({
    placeholder: {
        id: '',
        text: ''
      },
      allowClear: true
    });
@endpush
