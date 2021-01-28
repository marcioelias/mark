@extends('layouts.contentLayoutMaster')

@section('title', 'Comprar SMS')

@section('content')
<div class="card pb-0">
    <div class="card-body">
        <div class="row">
            @foreach ($packages as $package)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card text-white bg-gradient-primary text-center mb-0">
                    <div class="card-content d-flex">
                        <div class="card-body mb-0">
                            <p class="font-large-2 text-center"><i class="fas fa-sms"></i> {{ $package->sms_amount }}</p>
                            <h5 class="card-title text-white mt-3"><small>R$</small> {{ number_format($package->package_value, 2, ',', '.') }}</h5>
                            <p class="card-text">{{ $package->sms_package_name }}</p>
                            <script
                                src="https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js"
                                data-preference-id="{{ $mercadoPago->sellSMSPackage($package)->id }}" data-button-label="Comprar Agora!"
                                data-elements-color="#7367f0">
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/scripts/jquery.mask.min.js') }}"></script>
    @stack('custom-script')
@endsection
