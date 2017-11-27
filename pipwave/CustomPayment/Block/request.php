<?php
namespace //...

class PipwaveSendRequest()

{
private function _pipwave_wc_send_request($data) {
            // test mode is on
            if ($this->test_mode == 'yes') {
                $url = "https://staging-api.pipwave.com/payment";
            } else {
                $url = "https://api.pipwave.com/payment";
            }

            $agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
            $ch = curl_init();
			curl_setopt($ch, CURLOPT_PROXY, ‘my-proxy.offgamers.lan:3128’);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:' . $this->api_key));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $response = curl_exec($ch);
            if ($response == false) {
                echo "<pre>";
                echo 'CURL ERROR: ' . curl_errno($ch) . '::' . curl_error($ch);
                die;
            }
            curl_close($ch);

            return json_decode($response, true);
        }
}