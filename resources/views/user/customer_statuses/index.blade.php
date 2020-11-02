@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $customerStatuses,
    'model' => 'customer_status',
    'tableTitle' => 'Status de Cliente',
    'displayField' => 'customer_status',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])

@push('document-ready')
    if ({!! $accessDenied ?? 'false' !!}) {
        Swal.fire({
            title: 'Oops...',
            text: 'Status padrão do sitema, não pode ser alterado!',
            icon: 'warning'
        })
    }
@endpush
