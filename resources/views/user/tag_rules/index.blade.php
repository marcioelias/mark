@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $tagRules,
    'model' => 'tag_rule',
    'tableTitle' => 'Regras',
    'displayField' => 'lead_status',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
