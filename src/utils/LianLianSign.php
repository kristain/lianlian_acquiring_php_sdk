<?php
namespace lianlianpay\v3sdk\utils;

class LianLianSign
{
    public function sign(&$data, $privateKey)
    {
        $signContent = $this->gen_sign_content($data);
        return $this->gen_sign($signContent, $privateKey);
    }

    public function sign_text($data, $privateKey)
    {
        return $this->gen_sign($data, $privateKey);
    }

    public function verify(&$data, $sign, $pubKey)
    {
        $signContent = $this->gen_sign_content($data);
        return $this->verify_sign($signContent, $sign, $pubKey);
    }

    public function verify_text($data, $sign, $pubKey)
    {
        return $this->verify_sign($data, $sign, $pubKey);
    }

    /**
     * 生成签名内容
     * @param $req
     * @return string
     */
    private function gen_sign_content(&$req)
    {
        $arr = array($req);
        $strs = array();
        ksort($arr);
        $this->items(0, $arr, $strs);
        $msg = implode('&', $strs);
        return $msg;
    }

    /**
     * 递归深度优先排序
     * @param $x
     * @param $y
     * @param $strs
     */
    private function items($x, $y, &$strs)
    {
        if ($y == null) {
            return;
        }
        if (is_array($y)) {
            ksort($y);
            foreach ($y as $key => $value) {
                if($value != null){
                $this->items($key, $value, $strs);
                }
            }
            return;
        }
        $strs[] = $x . "=" . $y;
    }

    /**
     * 生成签名
     * @param $toSign
     * @param $privateKey
     * @return string
     */
    public function gen_sign($toSign, $privateKey)
    {
        //这里他是拼接成和pem文件一样的格式
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        $key = openssl_get_privatekey($privateKey);
        openssl_sign($toSign, $signature, $key);
        openssl_free_key($key);
        $sign = base64_encode($signature);
        return $sign;
    }

    /**
     * 验证签名
     * @param $data
     * @param $sign
     * @param $pubKey
     * @return bool
     */
    public function verify_sign($data, $sign, $pubKey)
    {
        $sign = base64_decode($sign);

        $pubKey = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";

        $key = openssl_pkey_get_public($pubKey);
        $result = openssl_verify($data, $sign, $key, OPENSSL_ALGO_SHA1) === 1;
        return $result;
    }

    public function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        $arr = null;
        foreach ($_arr as $key => $val) {
            $val = (is_array($val)) || is_object($val) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }
}
