@extends('layouts.crud.index', ['tableConf' => [
    'captions' => $fields,
    'rows' => $leads,
    'model' => 'lead',
    'tableTitle' => 'Leads',
    'displayField' => 'customer_name',
    'actions' => ['show'],
    'orderData' => $orderData ?? false,
    'searchParms' => 'user.leads.filters',
    'searchParmsData' => [
        'products' => $products,
        'paymentTypes' => $paymentTypes,
        'leadStatuses' => $leadStatuses,
        'tags' => $tags]
]])
