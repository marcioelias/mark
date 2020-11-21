@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $sentMessages,
    'model' => 'sent_message',
    'tableTitle' => 'Mensagens Enviadas',
    'displayField' => 'id',
    'actions' => ['show'],
    'orderData' => $orderData ?? false
]])
