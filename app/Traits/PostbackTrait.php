<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait PostbackTrait {

    public function getDriver() {
        return config('postback.drivers')[config('postback.default')];
    }

    public function getMyKey() {
        return $this->getDriver()['key'];
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function receive(Request $request) {
        $postback = $request->all();

        if (!$this->validateKey($postback, $this->getMyKey())) {
            return $this->response(401, 'Unauthorized');
        }

        return $this->handle($postback);
    }

    /**
     * Chech
     *
     * @param array $postback
     * @param string $myKey
     * @return boolean
     */
    public function validateKey(array $postback, string $myKey) {
        return ((string) $postback[$this->getPostbackKeyFieldName()] === (string) $myKey);
    }

    /**
     * Retur a response to be sent
     *
     * @param integer $status
     * @param string $message
     * @return response
     */
    public function response(int $status, string $message) {
        return response()->json(['message' => $message], $status);
    }

    public function getPostbackKeyFieldName() {
        return 'key';
    }
}
