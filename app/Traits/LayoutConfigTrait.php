<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Arr;

trait LayoutConfigTrait {
    public $paginate = 15;

    public $pageConfigs = [
        'theme' => 'light',
        'navbarColor' => 'bg-light',
        'navbarType' => 'floating',
        'footerType' => 'static',
        'bodyClass' => 'testClass'
    ];

    /* order properties */
    public $orderField = 'id';
    public $orderType = 'asc';

    public function fields() {
        return array(
            'id'
        );
    }

    public function getOrderData() {
        return [
            'order_by' => $this->orderField,
            'order_type' => $this->orderType
        ];
    }

    public function setOrder(HttpRequest $request, $default = false) {
        if ($request->order_by && Arr::has($this->fields(), $request->order_by)) {
            $this->orderField = $request->order_by;
            $this->orderType = in_array($request->order_type, ['ASC', 'DESC']) ? $request->order_type : 'ASC';
        } else if ($default) {
            $this->orderField = $default['order_by'];
            $this->orderType = $default['order_type'] ?? 'ASC';
        }
    }

    //chevron-down - chevron-up - chevrons-down - chevrons-up

    public function getBreadcrumbs() {
        if (Request::is('admin') || (Request::is(''))) {
            $home = [];
        } else if (Auth::guard('admin')->check()) {
            $home = [[
                'link' => '/admin',
                'name' => 'Home'
            ]];
        } else {
            $home = [[
                'link' => '/',
                'name' => 'Home'
            ]];
        }

        return array_merge($home, $this->breadcrumbs ?? []);
    }

    public function getView(string $view) {
        return view($view, ['pageConfigs' => $this->pageConfigs])
                    ->withBreadcrumbs($this->getBreadcrumbs());
    }

    public function getIndex(string $view) {
        return $this->getView($view)
                    ->withFields($this->fields())
                    ->withOrderData($this->getOrderData());
    }
}
