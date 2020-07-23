<?php

namespace App\Constants;

class PostbackEventType {
    public const ABANDONO_CHECKOUT  = '6106e1cc-59c3-4532-94d9-be4600cb2f19';
    public const IMPRESSAO_BOLETO   = '8189606f-3d6e-470d-9ee8-7f94a61d1327';
    public const BOLETO_VENCENDO    = '32d56274-3131-41f4-8a7a-a981a4264572';
    public const BOLETO_VENCIDO     = '6bc14a06-4cbf-4955-a2df-4215dcc51ba5';
    public const COMPRA_FINALIZADA  = '85b0f9e0-4d3f-4c08-ad29-c65015a01b1d';
    public const COMPRA_CANCELADA   = 'da6bd873-b04a-4f6c-bf6d-47757db96df6';
}
