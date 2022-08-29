<?php
namespace lianlianpay\v3sdk\service;

use lianlianpay\v3sdk\core\PaySDK;
use lianlianpay\v3sdk\client\HttpClient;
use lianlianpay\v3sdk\utils\LianLianSign;

class Refund
{
    private $pay_sdk;
    private $http_client;
    private $sign_tools;

    public function __construct() {
        $this->pay_sdk = PaySDK::getInstance();
        $this->http_client = new HttpClient();
        $this->sign_tools = new LianLianSign();
    }

    public function refund($refund_request, $private_key = '', $public_key = '') {

        $refund_url = sprintf($this->pay_sdk->refund_url, $refund_request->merchant_id, $refund_request->original_transaction_id);
        file_put_contents('log.txt',"refund_url=$refund_url\n");

        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $signature = $this->sign_tools->sign($this->sign_tools->object_to_array($refund_request), $private_key);
        $header = array(
            'sign-type: ' . 'RSA',
            'timestamp: ' . date("YmdHis", time()),
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: '.$signature
        );

        $result = $this->http_client->post($refund_url, $header, $refund_request);
        $response = $result['body'];
        $header_res = $result['header'];

        $return_code = $response['return_code'];
        if ($return_code != 'SUCCESS') {
            return $response;
        }
        if (empty($header_res['signature'][0])) {
            $response['sign_verify'] = false;
            return $response;
        }
        $check = $this->sign_tools->verify($response, $header_res['signature'][0], $public_key);
        $response['sign_verify'] = $check;
        return $response;
    }

    public function refund_query($merchant_id, $merchant_transaction_id, $private_key = '', $public_key = '') {

        $refund_query_url = sprintf($this->pay_sdk->refund_query_url, $merchant_id, $merchant_transaction_id);
        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $signature = $this->sign_tools->sign_text("merchant_id=$merchant_id&merchant_transaction_id=$merchant_transaction_id", $private_key);
        $header = array(
            'sign-type: ' . 'RSA',
            'timestamp: ' . date("YmdHis", time()),
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: '.$signature
        );

        $result = $this->http_client->get($refund_query_url, $header);
        $response = $result['body'];
        $header_res = $result['header'];

        $return_code = $response['return_code'];
        if ($return_code != 'SUCCESS') {
            return $response;
        }
        if (empty($header_res['signature'][0])) {
            $response['sign_verify'] = false;
            return $response;
        }
        $check = $this->sign_tools->verify($response, $header_res['signature'][0], $public_key);
        $response['sign_verify'] = $check;
        return $response;
    }
}