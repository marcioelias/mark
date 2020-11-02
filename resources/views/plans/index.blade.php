@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $plans,
    'model' => 'plan',
    'tableTitle' => 'Planos',
    'displayField' => 'name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
