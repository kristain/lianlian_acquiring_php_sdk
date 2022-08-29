<?php

namespace lianlianpay\v3sdk\service;

use lianlianpay\v3sdk\client\HttpClient;
use lianlianpay\v3sdk\utils\LianLianSign;
use lianlianpay\v3sdk\core\PaySDK;

class ShippingUpload
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

    //物流上传
    public function upload($shippingUploadRequest, $private_key = '', $public_key = '')
    {
        $shipping_upload_url = sprintf($this->pay_sdk->shipping_upload_url, $shippingUploadRequest->merchant_id, $shippingUploadRequest->merchant_transaction_id);
        file_put_contents('log.txt', "shipping_upload_url=$shipping_upload_url\n", FILE_APPEND);

        if (empty($private_key)) {
            $private_key = $this->pay_sdk->private_key;
        }
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $arr = $this->sign_tools->object_to_array($shippingUploadRequest);

        $signature = $this->sign_tools->sign($arr, $private_key);

        $header = [
            'sign-type: ' . 'RSA',
            'timestamp: ' . date("YmdHis", time()),
            'timezone: ' . date_default_timezone_get(),
            'Content-Type: ' . 'application/json',
            'signature: '.$signature
        ];

        $result = $this->http_client->post($shipping_upload_url, $header, $shippingUploadRequest);
        $response = $result['body'];
        $header_res = $result['header'];
//        file_put_contents('log.txt', "response=" . json_encode($result) . "\n", FILE_APPEND);
//        file_put_contents('log.txt', "header_res=$header_res\n", FILE_APPEND);

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
