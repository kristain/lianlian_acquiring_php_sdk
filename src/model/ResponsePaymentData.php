<?php
namespace lianlianpay\v3sdk\model;

class ResponsePaymentData
{
    public $payment_currency_code;
    public $payment_amount;
    public $exchange_rate;
    public $payment_time;
    public $payment_status;
    public $settlement_currency_code;
    public $settlement_amount;
    public $installments;
    public $account_date;
}