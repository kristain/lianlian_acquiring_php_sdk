<?php
namespace lianlianpay\v3sdk\model;

class ResponseRefundData
{
    public $refund_currency_code;
    public $refund_amount;
    public $settlement_currency_code;
    public $settlement_amount;
    public $actual_refund_currency_code;
    public $actual_refund_amount;
    public $refund_status;
    public $exchange_rate;
    public $refund_time;
    public $reason;
    public $account_date;
}