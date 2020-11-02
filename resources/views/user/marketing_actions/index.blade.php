@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $marketingActions,
    'model' => 'marketing_action',
    'tableTitle' => 'Ações de Marketing',
    'displayField' => 'marketing_action_description',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
