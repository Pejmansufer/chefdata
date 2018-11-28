<?php
 
 class Trustpilot_Reviews_Helper_HttpClient
 {
    const HTTP_REQUEST_TIMEOUT = 3;
    
    public function request($url, $httpRequest, $origin = null, $data = null, $params = array(), $timeout = self::HTTP_REQUEST_TIMEOUT)
    {    
        try{
            $ch = curl_init();
            $this->setCurlOptions($ch, $httpRequest, $data, $origin, $timeout);
            $url = $this->buildParams($url, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
            $content = curl_exec($ch);
            $responseData = json_decode($content);
            $responseInfo = curl_getinfo($ch);
            $responseCode = $responseInfo['http_code'];
            curl_close($ch);
            $response = array();
            $response['code'] = $responseCode;

            if ($responseData) {
                $response['data'] = $responseData;
            }
            return $response;
        } catch (Exception $e){
            //intentionally empty
        }
    }
 
    private  function jsonEncoder($data)
    {
        if (function_exists('json_encode'))
            return json_encode($data);
        elseif (method_exists('Tools', 'jsonEncode'))
            return Tools::jsonEncode($data);
    }


    private function setCurlOptions($ch, $httpRequest, $data, $origin, $timeout)
    { 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        if ($httpRequest == 'POST') {
            $encoded_data = $this->jsonEncoder($data);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'Content-Length: ' . strlen($encoded_data), 'Origin: ' . $origin));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encoded_data);
            return;
        } elseif ($httpRequest == 'GET') {
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            return;
        }
        return;
    }

    private function buildParams($url, $params = array()){
        if (!empty($params) && is_array($params)) {
            $url .= '?'.http_build_query($params);
        }
        return $url;
    }
 }