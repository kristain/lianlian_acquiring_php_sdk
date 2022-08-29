<?php
namespace lianlianpay\v3sdk\model;

class RequestPaymentData
{
    public $payment_currency_code;
    public $payment_amount;
    public $exchange_token;
    public $exchange_rate;
    public $settlement_currency_code;
    public $installments;
    public $card;
}