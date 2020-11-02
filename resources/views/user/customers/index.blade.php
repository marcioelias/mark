@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $customers,
    'model' => 'customer',
    'tableTitle' => 'Clientes',
    'displayField' => 'customer_name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false,
    'customMethods' => [
        ['component' => 'user.customers.import_action']
    ]
]])
