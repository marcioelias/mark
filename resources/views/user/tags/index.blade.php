@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $tags,
    'model' => 'tag',
    'tableTitle' => 'Tags',
    'displayField' => 'tag_name',
    'actions' => ['edit', 'destroy'],
    'orderData' => $orderData ?? false
]])
