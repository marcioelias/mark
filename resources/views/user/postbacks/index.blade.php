@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $postbacks,
    'model' => 'postback',
    'tableTitle' => 'Postbacks',
    'displayField' => 'product_name',
    'actions' => ['show'],
    'orderData' => $orderData ?? false
]])
