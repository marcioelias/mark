@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $users,
    'model' => 'user',
    'tableTitle' => 'Clientes',
    'displayField' => 'name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
