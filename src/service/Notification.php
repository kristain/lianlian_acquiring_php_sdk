<?php
namespace lianlianpay\v3sdk\service;

use demo\PayConstant;
use lianlianpay\v3sdk\core\PaySDK;
use lianlianpay\v3sdk\utils\LianLianSign;

class Notification
{

    private $pay_sdk;
    private $sign_tools;

    public function __construct()
    {
        $this->pay_sdk = PaySDK::getInstance();
        $this->sign_tools = new LianLianSign();
    }

    public function payment_notify($notify_body, $signature, $public_key = '')
    {
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $notify_body = preg_replace('/:\s*([0-9]*\.?[0-9]+)/', ': "$1"', $notify_body);
        $notify_data = json_decode($notify_body, true);
        file_put_contents('log.txt', "notify_body=$notify_body\n", FILE_APPEND);

        if (empty($signature)) {
            file_put_contents('log.txt', "signature=$signature\n", FILE_APPEND);
            echo json_encode(['code' => 401, 'msg' => "error"]);
        } else {
            if ($notify_data['payment_data']['exchange_rate']) {
                $notify_data['payment_data']['exchange_rate'] = sprintf('%.8f', $notify_data['payment_data']['exchange_rate']);
            }
            $notify_data['payment_data']['payment_amount'] = sprintf('%.2f', $notify_data['payment_data']['payment_amount']);
            if ($notify_data['payment_data']['settlement_amount']) {
                $notify_data['payment_data']['settlement_amount'] = sprintf('%.2f', $notify_data['payment_data']['settlement_amount']);
            }
            $check_result = $this->sign_tools->verify($notify_data, $signature, $public_key);
            file_put_contents('log.txt', "notify_data=" . json_encode($notify_data) . "\n", FILE_APPEND);
            file_put_contents('log.txt', "signature=$signature\n", FILE_APPEND);
            file_put_contents('log.txt', "public_key=$public_key\n", FILE_APPEND);

            if (!$check_result) {
                echo json_encode(['code' => 401, 'msg' => "error"]);
            } else {
                $payment_status = $notify_data['payment_data']['payment_status'];
                if ($payment_status == 'PS') {
                    echo json_encode(['code' => 200, 'msg' => "success"]);
                }
            }
        }

        return $notify_data;
    }

    public function refund_notify($notify_body, $signature, $public_key = '')
    {
        if (empty($public_key)) {
            $public_key = $this->pay_sdk->public_key;
        }

        $notify_body = preg_replace('/:\s*([0-9]*\.?[0-9]+)/', ': "$1"', $notify_body);
        $notify_data = json_decode($notify_body, true);

        if (empty($signature)) {
            echo json_encode(['code' => 401, 'msg' => "error"]);
        } else {
            if ($notify_data['refund_data']['exchange_rate']) {
                $notify_data['refund_data']['exchange_rate'] = sprintf('%.8f', $notify_data['refund_data']['exchange_rate']);
            }
            $notify_data['refund_data']['refund_amount'] = sprintf('%.2f', $notify_data['refund_data']['refund_amount']);
            if ($notify_data['refund_data']['settlement_amount']) {
                $notify_data['refund_data']['settlement_amount'] = sprintf('%.2f', $notify_data['refund_data']['settlement_amount']);
            }
            $check_result = $this->sign_tools->verify($notify_data, $signature, $public_key);
            file_put_contents('log.txt', "notify_data=" . json_encode($notify_data) . "\n", FILE_APPEND);
            file_put_contents('log.txt', "signature=$signature\n", FILE_APPEND);
            file_put_contents('log.txt', "public_key=$public_key\n", FILE_APPEND);
            if (!$check_result) {
                echo json_encode(['code' => 401, 'msg' => "error"]);
            } else {
                $refund_status = $notify_data['refund_data']['refund_status'];
                if ($refund_status == 'RS') {
                    echo json_encode(['code' => 200, 'msg' => "success"]);
                }
            }
        }

        return $notify_data;
    }
}