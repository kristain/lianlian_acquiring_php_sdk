<?php
namespace lianlianpay\v3sdk\model;

class RefundRequest
{
    public $merchant_transaction_id;
    public $merchant_id;
    public $sub_merchant_id;
    public $merchant_refund_time;
    public $original_transaction_id;
    public $refund_data;
    public $notification_url;
}