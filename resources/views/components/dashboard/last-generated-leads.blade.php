<div class="col-12">
    <div class="card">
        <div class="card-header mb-1">
            <h4 class="card-title">Últimas vendas recuperadas</h4>
            <div class="d-flex align-items-center">
                <p class="card-text font-small-2 mr-25 mb-0">últimas 10 vendas</p>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead style="background-color: #F3F2F7">
                        <tr>
                            <th>Cliente</th>
                            <th>Produto</th>
                            <th>Pagamento</th>
                            <th>Recuperado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leads as $lead)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="font-weight-bolder">{{ $lead->customer->customer_name }}</div>
                                        <div class="font-small-2 text-muted">{{ $lead->customer->customer_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span>{{ $lead->product->product_name }}</span>
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <span class="font-weight-bolder mb-25">{{ $lead->paymentType->payment_type }}</span>
                                    <span class="font-small-2 text-muted">{{ $lead->paid_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>R$ {{ number_format($lead->value, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>