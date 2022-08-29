<?php
namespace lianlianpay\v3sdk\client;

class HttpClient
{
    /**
     * 发送请求
     * @param $url
     * @param $headers
     * @param $request
     * @return mixed
     */
    public function post($url, $headers, $request)
    {
        $header_res = [];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$header_res) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $header_res[strtolower(trim($header[0]))][] = trim($header[1]);
                return $len;
            }
        );

        $response_data = curl_exec($curl);
        curl_close($curl);
        file_put_contents('log.txt', "response_data=$response_data\n", FILE_APPEND);

        $result = [];
        $result['body'] = json_decode($response_data, true);
        $result['header'] = $header_res;
        return $result;
    }

    public function get($url, $headers)
    {
        $header_res = [];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADERFUNCTION,
            function ($curl, $header) use (&$header_res) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) // ignore invalid headers
                    return $len;

                $header_res[strtolower(trim($header[0]))][] = trim($header[1]);
                return $len;
            }
        );

        $response_data = curl_exec($curl);
        curl_close($curl);

        $result = [];
        $result['body'] = json_decode($response_data, true);
        $result['header'] = $header_res;
        return $result;
    }
}