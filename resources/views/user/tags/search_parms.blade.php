@component('components.input-select', [
    'field' => 'product_id',
    'label' => 'Produto',
    'items' => App\Models\User\Product::get(),
    'displayField' => 'product_name',
    'keyField' => 'id',
    'name' => 'product_id',
    'id' => 'product_id',
    'div_css' => 'mb-1',
    'indexSelected' => $_GET['product_id'] ?? null,
    'defaultNone' => true
])
@endcomponent

@push('document-ready')
$("#product_id").change(function() {
    $("#searchForm").submit();
})
@endpush
