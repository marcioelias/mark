<span data-toggle="tooltip" data-placement="top" title="Conectar Whatsapp">
  <input type="hidden" name="whatsapp-instance-id" id="whatsapp-instance-id" value="{{ $data->id }}">
  <a href="" id="whatsapp-connect" class="btn btn-sm btn-success btn-connect-whatsapp icon-btn-sm-padding" style="font-size: 1.2rem"><i class="fas fa-qrcode"></i></a>
</span>
<span data-toggle="tooltip" data-placement="top" title="Desconectar Whatsapp">
  <input type="hidden" name="whatsapp-instance-id-disconnect" id="whatsapp-instance-id-disconnect" value="{{ $data->id }}">
  <a href="" id="whatsapp-connect" class="btn btn-sm btn-warning btn-disconnect-whatsapp icon-btn-sm-padding" style="font-size: 1.2rem"><i class="fas fa-power-off"></i></a>
</span>
