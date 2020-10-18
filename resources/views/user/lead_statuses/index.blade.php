@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $leadStatuses,
    'model' => 'lead_status',
    'tableTitle' => 'Status',
    'displayField' => 'status',
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
