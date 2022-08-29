<?php
namespace lianlianpay\v3sdk\core;

require_once 'PayConfig.php';

class PaySDK
{
    public $sandbox = false;
    public $pay_url = '';
    public $pay_query_url = '';
    public $refund_url = '';
    public $refund_query_url = '';
    public $pay_cancel_url = '';
    public $get_token_url = '';
    public $private_key = '';
    public $public_key = '';

    //创建静态私有的变量保存该类对象
    private static $instance;

    //防止使用new直接创建对象
    private function __construct() {

    }

    //防止使用clone克隆对象
    private function __clone() {

    }

    public static function getInstance()
    {
        //判断$instance是否是Singleton的对象，不是则创建
        if (!self::$instance instanceof self) {
            self::$instance = new self();
            self::$instance->init( false);
        }
        return self::$instance;
    }

    public function init($sandbox = false) {
        self::$instance->sandbox = $sandbox;
        if ($sandbox) {
            self::$instance->pay_url = PayConfig::$sandbox_base_url . PayConfig::$payment_path;
            self::$instance->pay_query_url = PayConfig::$sandbox_base_url . PayConfig::$payment_query_path;
            self::$instance->refund_url = PayConfig::$sandbox_base_url . PayConfig::$refund_path;
            self::$instance->refund_query_url = PayConfig::$sandbox_base_url . PayConfig::$refund_query_path;
            self::$instance->shipping_upload_url = PayConfig::$sandbox_base_url . PayConfig::$shipping_upload_path;
            self::$instance->pay_cancel_url = PayConfig::$sandbox_base_url . PayConfig::$payment_cancel_path;
            self::$instance->get_token_url = PayConfig::$sandbox_base_url . PayConfig::$payment_token_path;
        } else {
            self::$instance->pay_url = PayConfig::$production_base_url . PayConfig::$payment_path;
            self::$instance->pay_query_url = PayConfig::$production_base_url . PayConfig::$payment_query_path;
            self::$instance->refund_url = PayConfig::$production_base_url . PayConfig::$refund_path;
            self::$instance->refund_query_url = PayConfig::$production_base_url . PayConfig::$refund_query_path;
            self::$instance->shipping_upload_url = PayConfig::$production_base_url . PayConfig::$shipping_upload_path;
            self::$instance->pay_cancel_url = PayConfig::$production_base_url . PayConfig::$payment_cancel_path;
            self::$instance->get_token_url = PayConfig::$production_base_url . PayConfig::$payment_token_path;
        }
    }

    public function setKey($private_key, $public_key) {
        self::$instance->private_key = $private_key;
        self::$instance->public_key = $public_key;
    }
}