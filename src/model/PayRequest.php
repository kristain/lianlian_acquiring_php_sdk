<?php
namespace lianlianpay\v3sdk\model;

class PayRequest
{
    public $merchant_transaction_id;
    public $merchant_id;
    public $sub_merchant_id;
    public $payment_method;
    public $biz_code;
    public $additional_info;
    public $notification_url;
    public $redirect_url;
    public $cancel_url;
    public $country;
    public $front_model;
    public $customer;
    public $merchant_order;
    public $payment_data;
    public $terminal_data;
}