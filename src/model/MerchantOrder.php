<?php
namespace lianlianpay\v3sdk\model;

class MerchantOrder
{
    public $merchant_order_id;
    public $merchant_user_no;
    public $merchant_order_time;
    public $order_description;
    public $due_date;
    public $mcc;
    public $order_amount;
    public $order_currency_code;
    public $tax;
    public $products;
    public $shipping;
}