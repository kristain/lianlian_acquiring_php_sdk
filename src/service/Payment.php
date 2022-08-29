<?php

namespace lianlianpay\v3sdk\service;

use lianlianpay\v3sdk\client\HttpClient;
use lianlianpay\v3sdk\core\PaySDK;
use lianlianpay\v3sdk\utils\LianLianSign;

class Payment
{
    private $pay_sdk;
    private $http_client;
    private $sign_tools;

    public function __construct()
    {
        $this->pay_sdk = PaySDK::getInstance();
        $this->http_client = new HttpClient();
        $this->sign_tools = new LianLianSign();
    }

    public function pay($pay_request, $private_key = '', $public_key = '')
    {

        $pay_url = sprintf($this->pay_sdk->pay_url, $pay_request->merchant_id);
        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $arr = $this->sign_tools->object_to_array($pay_request);
        $signature = $this->sign_tools->sign($arr, $private_key);
        $header = [
            'sign-type: ' . 'RSA',
            'timestamp: ' . date("YmdHis", time()),
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: ' . $signature
        ];

        $result = $this->http_client->post($pay_url, $header, $arr);

        if (empty($result['body'])) {
            return null;
        }

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

    //支付查询
    public function pay_query($merchant_id, $merchant_transaction_id, $private_key = '', $public_key = '')
    {
        $pay_url = sprintf($this->pay_sdk->pay_query_url, $merchant_id, $merchant_transaction_id);
        file_put_contents('log.txt', "pay_url=$pay_url\n", FILE_APPEND);

        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $signature = $this->sign_tools->sign_text("merchant_id=$merchant_id&merchant_transaction_id=$merchant_transaction_id", $private_key);
        $header = [
            'sign-type: ' . 'RSA',
            'timestamp: ' . date("YmdHis", time()),
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: ' . $signature
        ];

        $result = $this->http_client->get($pay_url, $header);
        $response = $result['body'];
        $header_res = $result['header'];
        file_put_contents('log.txt', "response=" . json_encode($result) . "\n", FILE_APPEND);
        file_put_contents('log.txt', "header_res=$header_res\n", FILE_APPEND);

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

    //支付取消
    public function pay_cancel($merchant_id, $merchant_transaction_id, $private_key = '', $public_key = '')
    {
        $pay_url = sprintf($this->pay_sdk->pay_cancel_url, $merchant_id, $merchant_transaction_id);

        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $signature = $this->sign_tools->sign_text("merchant_id=$merchant_id&merchant_transaction_id=$merchant_transaction_id", $private_key);
        $header = [
            'sign-type: ' . 'RSA',
            'timestamp: ' . date("YmdHis", time()),
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: ' . $signature
        ];

        $result = $this->http_client->get($pay_url, $header);
        $response = $result['body'];
        $header_res = $result['header'];
        file_put_contents('log.txt', "response=" . json_encode($result) . "\n", FILE_APPEND);
        file_put_contents('log.txt', "header_res=$header_res\n", FILE_APPEND);

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

    //获取iframe支付token
    public function get_token($merchant_id, $private_key = '', $public_key = '')
    {
        $pay_url = sprintf($this->pay_sdk->get_token_url, $merchant_id);

        //file_put_contents('log.txt', "pay_url=$pay_url\n", FILE_APPEND);
        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }
        $timestamp = date("YmdHis", time());
        $signature = $this->sign_tools->sign_text("merchant_id=$merchant_id&timestamp=$timestamp", $private_key);
        $header = [
            'sign-type: ' . 'RSA',
            'timestamp: ' . $timestamp,
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: ' . $signature
        ];
        $result = $this->http_client->get($pay_url, $header);

        $response = $result['body'];
        $header_res = $result['header'];
        // file_put_contents('log.txt', "response=" . json_encode($result) . "\n", FILE_APPEND);
        // file_put_contents('log.txt', "header_res=$header_res\n", FILE_APPEND);

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
