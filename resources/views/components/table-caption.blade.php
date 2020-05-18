@php
    $lastOrderBy = $_GET['order_by'] ?? false;
    $lastOrderType = $_GET['order_type'] ?? 'ASC';
    $orderType = ($field == $lastOrderBy) ? ($lastOrderType == 'ASC') ? 'DESC' : 'ASC' : 'ASC';

    if ($field == $orderData['order_by']) {
        $icon = $orderData['order_type'] == 'ASC' ? 'chevrons-down' : 'chevrons-up';
    } else {
        $icon = 'code';
    }
    $label = is_array($caption) ? __($caption['label']) : __($caption);
    $bg_color = $field == $orderData['order_by'] ? 'warning' : 'primary';
@endphp

<th class="p-1 bg-{{ $bg_color }}">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            {{ $label }}
        </div>
        <div>
            <a href="{{ url()->current().'?'.Arr::query(array_merge(Request::query(), [
                    'order_by' => $field,
                    'order_type' => $orderType
                ])) }}" class="btn btn-{{ $bg_color }} btn-table-order"
                data-toggle="tooltip"
                data-placement="top"
                title="Ordenar por {{ $label }}">
                <i class="feather icon-{{ $icon }}"></i>
            </a>
        </div>
    </div>
</th>
