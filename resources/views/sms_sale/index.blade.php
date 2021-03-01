@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $smsSales,
    'model' => 'sms_sale',
    'tableTitle' => 'Pacotes de SMS',
    'displayField' => 'sms_package_name',
    'actions' => ['destroy'],
    'orderData' => $orderData ?? false
]])
