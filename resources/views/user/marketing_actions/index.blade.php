@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $marketingActions,
    'model' => 'marketing_action',
    'tableTitle' => 'Ações de Marketing',
    'displayField' => 'marketing_action_description',
    'actions' => ['show', 'destroy'],
    'orderData' => $orderData ?? false
]])
