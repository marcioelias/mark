@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $whatsappInstances,
    'model' => 'whatsapp_instance',
    'tableTitle' => 'Instâncias Whatsapp',
    'displayField' => 'description',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
