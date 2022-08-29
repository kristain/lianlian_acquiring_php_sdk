<?php
namespace lianlianpay\v3sdk\core;

class PayConfig
{
    public static $sandbox_base_url = 'https://celer-api.LianLianpay-inc.com/v3';
    public static $production_base_url = 'https://gpapi.lianlianpay.com/v3';
    public static $payment_path = '/merchants/%s/payments';
    public static $payment_query_path = '/merchants/%s/payments/%s';
    public static $refund_path = '/merchants/%s/payments/%s/refunds';
    public static $refund_query_path = '/merchants/%s/refunds/%s';
    public static $shipping_upload_path = '/merchants/%s/payments/%s/shipments';
    public static $payment_cancel_path = '/merchants/%s/payments/%s/cancelpay';
    public static $payment_token_path = '/merchants/%s/token';
}
