@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $products,
    'model' => 'product',
    'tableTitle' => 'Products',
    'displayField' => 'product_name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
