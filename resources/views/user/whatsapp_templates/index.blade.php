@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $whatsappTemplates,
    'model' => 'whatsapp_template',
    'tableTitle' => 'Template de Msg',
    'displayField' => 'template_name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
