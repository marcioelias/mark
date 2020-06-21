@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $funnels,
    'model' => 'funnel',
    'tableTitle' => 'Funis',
    'displayField' => 'id',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
