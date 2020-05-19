<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface Postback {

    /**
     * Receive a postback message
     *
     * @param Request $request
     * @return void
     */
    public function receive(Request $request);

    /**
     * Chech
     *
     * @param array $postback
     * @param string $myKey
     * @return void
     */
    public function validateKey(array $postback, string $myKey);

    /**
     * Process the received postback
     *
     * @param array $postback
     * @return void
     */
    public function handle(array $postback);

    /**
     * Retur a response to be sent
     *
     * @param integer $status
     * @param string $message
     * @return response
     */
    public function response(int $status, string $message);
}
