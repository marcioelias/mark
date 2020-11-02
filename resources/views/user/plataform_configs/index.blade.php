@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $plataformConfigs,
    'model' => 'plataform_config',
    'tableTitle' => 'Plataform Config',
    'displayField' => 'plataform_name',
    'actions' => [['custom_action' => 'components.customActions.url-postback'], 'edit', 'destroy'],
    'orderData' => $orderData ?? false
]])

@push('document-ready')
const copyUrl = (content) => {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(content).select();
    document.execCommand("copy");
    $temp.remove();
}
$('.btnUrlPostback').on('click', event => {
    const plataformConfigUUID = $(event.target).closest('span').find('#config-id').val()
    event.preventDefault()
    $.get('/plataform_config/get_url/'+plataformConfigUUID, res => {
      const webhookURL = res.url
      Swal.fire({
        title: 'URL para Postback',
        icon: 'info',
        heightAuto: false,
        html: '<h5 class="text-secondary">Configure na sua plataforma</h5>' +
          '<fieldset class="position-relative has-icon-left input-divider-left">' +
          '  <input type="text" name="webhookURLField" class="form-control" id="webhookURLField" value="'+webhookURL+'">' +
          '  <div class="form-control-position">' +
          '      <i class="feather icon-link"></i>' +
          '  </div>' +
          '</fieldset>',
          showCloseButton: true,
        showCancelButton: true,
        confirmButtonText:
          '<i class="feather icon-copy"></i> Copiar',
        cancelButtonText:
          '<i class="feather icon-x"></i> Cancelar',
      }).then(result => {
        if (result.value) {
            copyUrl(webhookURL)
        }
      })
    })
})
@endpush
