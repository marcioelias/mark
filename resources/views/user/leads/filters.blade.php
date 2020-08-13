<div class="row d-none" id="filter-fields">
    <input type="hidden" name="showing_filters" id="showing-filters" value="{{ $_GET['showing_filters'] ?? false }}">
    <div class="col-md-4 mb-1">
        <label for="lead_dt_begin">Data de compra - Início</label>
        <input type="text" value="{{ $_GET['lead_dt_begin'] ?? '' }}" id="lead_dt_begin" name="lead_dt_begin" class="form-control pickadate">
    </div>
    <div class="col-md-4 mb-1">
        <label for="lead_dt_end">Data de compra - Fim</label>
        <input type="text" value="{{ $_GET['lead_dt_end'] ?? '' }}" id="lead_dt_end" name="lead_dt_end" class="form-control pickadate">
    </div>
    <div class="col-md-4 mb-1">
        <label for="tag_id">Tag</label>
        <select name="tag_id" id="tag_id" class="form-control select2 submit-on-change" data-width="100%">
            <option value="" {{ !isset($_GET['tag_id']) ? 'selected' : '' }} disabled></option>
            <option value="none" {{ isset($_GET['tag_id']) && $_GET['tag_id'] === 'none' ? 'selected' : '' }}>Sem Tag</option>
            @foreach ($tags as $tag)
            <option value="{{ $tag->id }}" {{ (isset($_GET['tag_id']) && $_GET['tag_id'] === $tag->id) ? 'selected' : '' }}>{{ $tag->tag_name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-1">
        <label for="product_id">Produto</label>
        <select name="product_id" id="product_id" class="form-control select2 submit-on-change" data-width="100%">
            <option value="" {{ !isset($_GET['product_id']) ? 'selected' : '' }} disabled></option>
            @foreach ($products as $product)
            <option value="{{ $product->id }}" {{ (isset($_GET['product_id']) && $_GET['product_id'] === $product->id) ? 'selected' : '' }}>{{ $product->product_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-1">
        <label for="payment_type_id">Forma de Pagamento</label>
        <select name="payment_type_id" id="payment_type_id" class="form-control select2 submit-on-change" data-width="100%">
            <option value="" {{ !isset($_GET['payment_type_id']) ? 'selected' : '' }} disabled></option>
            @foreach ($paymentTypes as $paymentType)
            <option value="{{ $paymentType->id }}" {{ (isset($_GET['payment_type_id']) && $_GET['payment_type_id'] === $paymentType->id) ? 'selected' : '' }}>{{ $paymentType->payment_type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 mb-1">
        <label for="lead_status_id">Status do Lead</label>
        <select name="lead_status_id" id="lead_status_id" class="form-control select2 submit-on-change" data-width="100%">
            <option value="" {{ !isset($_GET['lead_status_id']) ? 'selected' : '' }} disabled></option>
            @foreach ($leadStatuses as $leadStatus)
            <option value="{{ $leadStatus->id }}" {{ (isset($_GET['lead_status_id']) && $_GET['lead_status_id'] === $leadStatus->id) ? 'selected' : '' }}>{{ $leadStatus->status }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row" >
    <div class="col d-flex justify-content-end">
        <button class="btn btn-success d-none" id="btn-filter" ><i class="fa fa-filter"></i> Aplicar Filtros</button>
    </div>
</div>
<div class="row">
    <div class="col">
        <button class="btn btn-primary btn-sm btn-block pb-0" id="show-filters" data-toggle="tooltip" title="Mostrar opções de filtro"><i class="fa fa-arrow-down fa-2x"></i></button>
        <button class="btn btn-primary btn-sm btn-block pt-0 d-none" id="hide-filters" data-toggle="tooltip" title="Esconder opções de filtro"><i class="fa fa-arrow-up fa-2x"></i></button>
    </div>
</div>

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
@endsection

@push('custom-scripts')
<script src="{{ asset(mix('js/scripts/sweetalert2.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/lib/translations/pt_BR.js')) }}"></script>
@endpush

@push('document-ready')
    $('.pickadate').pickadate({
        //format: 'You selected: dddd, dd mmm, yyyy',
        formatSubmit: 'yyyy/mm/dd'
    })

    const onChange = formattedValue => {
        console.log('New value:', formattedValue)
    }
    $('#lead_dt_begin').on('pickadate:change', onChange)


    if ($('#showing-filters').val() == 'true') {
        $('#filter-fields').removeClass('d-none')
        $('#hide-filters').removeClass('d-none')
        $('#btn-filter').removeClass('d-none')
        $('#show-filters').addClass('d-none')
    } else {
        $('#filter-fields').addClass('d-none')
        $('#hide-filters').addClass('d-none')
        $('#btn-filter').addClass('d-none')
        $('#show-filters').removeClass('d-none')
    }

    $('#btn-filter').on('click', (e) => {
        $("#searchForm").submit();
    })

    {{-- $('.submit-on-change')
        .on('select2:select', (e) => {
            $("#searchForm").submit();
        })
        .on('select2:clear', (e) => {
            $("#searchForm").submit();
        })
 --}}
    $('#show-filters').on('click', (e) => {
        e.preventDefault()
        $('#filter-fields').removeClass('d-none')
        $('#hide-filters').removeClass('d-none')
        $('#show-filters').addClass('d-none')
        $('#btn-filter').removeClass('d-none')
        $('#showing-filters').val(true)
    })

    $('#hide-filters').on('click', (e) => {
        e.preventDefault()
        $('#filter-fields').addClass('d-none')
        $('#hide-filters').addClass('d-none')
        $('#show-filters').removeClass('d-none')
        $('#btn-filter').addClass('d-none')
        $('#showing-filters').val(false)
    })
@endpush
