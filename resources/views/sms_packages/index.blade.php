@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $smsPackages,
    'model' => 'sms_package',
    'tableTitle' => 'Pacotes de SMS',
    'displayField' => 'sms_package_name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
