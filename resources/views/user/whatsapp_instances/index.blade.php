@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $whatsappInstances,
    'model' => 'whatsapp_instance',
    'tableTitle' => 'Instâncias Whatsapp',
    'displayField' => 'description',
    'actions' => [['custom_action' => 'components.customActions.whatsapp-connect'], 'edit', 'destroy'],
    'orderData' => $orderData ?? false
]])

@push('document-ready')
$('.btn-connect-whatsapp').on('click', event => {
    const whatsappInstance = $(event.target).closest('span').find('#whatsapp-instance-id').val()
    event.preventDefault()
    $.get('/whatsapp_instance/'+whatsappInstance+'/qrcode', res => {
        Swal.fire({
            imageUrl: `${res.qrcode}`
        })
    })
})
$('.btn-disconnect-whatsapp').on('click', async event => {
    const whatsappInstance = $(event.target).closest('span').find('#whatsapp-instance-id-disconnect').val()
    event.preventDefault()
    await $.ajax({
        url: '/whatsapp_instance/'+whatsappInstance+'/disconnect',
        type: 'POST',
        dataType: 'json',
        data: {},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: res => {
            if (res.data) {
                Swal.fire({
                    title: 'Concluído',
                    text: 'Instância desconectada com sucesso!',
                    icon: 'success'
                }).then(function() {
                    location.reload(true)
                })
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: 'Algo deu errado!',
                    icon: 'error'
                })
            }
        }
    })
})
@endpush
