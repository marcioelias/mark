@extends('layouts.contentLayoutMaster')


@section('title', 'Floating Navbar')

@section('vendor-style')
        <!-- vendor css files -->
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/ui/prism.min.css')) }}">
@endsection
@section('content')
    teste
@endsection
@section('vendor-script')
        <!-- vendor files -->
        <script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
@endsection
