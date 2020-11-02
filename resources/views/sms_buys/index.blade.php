@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $smsBuys,
    'model' => 'sms_buy',
    'tableTitle' => 'Compras de SMS',
    'displayField' => 'created_at',
    'actions' => ['destroy'],
    'orderData' => $orderData ?? false
]])
