@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard Analytics')

@section('vendor-style')
<!-- vendor css files -->
{{-- <link rel="stylesheet" href="{{ asset(mix('vendors/css/charts/apexcharts.css')) }}"> --}}
@endsection
@section('page-style')
<!-- Page css files -->
<link rel="stylesheet" href="{{ asset(mix('css/pages/dashboard-analytics.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/pages/card-analytics.css')) }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6 col-12">
            <x-dashboard.total-email-messages-sent />
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <x-dashboard.total-whatsapp-messages-sent />
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <x-dashboard.total-sms-messages-sent />
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <x-dashboard.total-customers />
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <x-dashboard.paid-billet />
        </div>
        <div class="col-md-6 col-12">
            <x-dashboard.paid-credit />
        </div>
    </div>
    <div class="row">
        <x-dashboard.monthly-sale />
    </div>
    <div class="row">
        <x-dashboard.monthly-conv />
    </div>
    <div class="row">
        <x-dashboard.last-generated-leads />
    </div>
    
    <div class="row">
        <div class="col-md-4 col-12">

        </div>
    </div>
    <x-dashboard.plan-usage-statistics />
    {{-- <div class="row">
        <div class="col-md-4 col-12">
            <x-dashboard.plan-usage />
        </div>
        <div class="col-md-4 col-12">
            <x-dashboard.convert-percentage percent="10"/>
        </div>
    </div> --}}
@endsection

@section('vendor-script')
<!-- vendor files -->
<script src="{{ asset(mix('vendors/js/charts/apexcharts.min.js')) }}"></script>
@endsection
@section('page-script')
<!-- Page js files -->
{{-- <script src="{{ asset(mix('js/scripts/pages/dashboard-analytics.js')) }}"></script> --}}
@stack('component-script')
@endsection
