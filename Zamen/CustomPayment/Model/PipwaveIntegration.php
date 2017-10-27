<?php
namespace Zamen\CustomPayment\Model;

class PipwaveIntegration {

    function generate_pw_signature($signatureParam) {
        ksort($signatureParam);
        $signature = "";
        foreach ($signatureParam as $key => $value) {
            $signature .= $key . ':' . $value;
        }
        return sha1($signature);
    }
	
	function get_agent() {
		$agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)";
		return $agent;
	}

    function send_request_to_pw($data, $pw_api_key, $url, $agent) {
		
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, 'my-proxy.offgamers.lan:3128');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:' . $pw_api_key));
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


    protected $result;
    function render_sdk($response, $token, $api_key, $sdk_url, $loading_img){
        if ($response['status'] == 200) {
            $api_data = json_encode([
                'api_key' => $api_key,
                'token' => $token
            ]);
            
            $this->result = <<<EOD
                    <div id="pwscript" class="text-center"></div>
                    <div id="pwloading" style="text-align: center;">
                        <img src="$loading_img" />
                    </div>
                    <script type="text/javascript">
                        var pwconfig = $api_data;
                        (function (_, p, w, s, d, k) {
                            var a = _.createElement("script");
                            a.setAttribute('src', w + d);
                            a.setAttribute('id', k);
                            setTimeout(function() {
                                var reqPwInit = (typeof reqPipwave != 'undefined');
                                if (reqPwInit) {
                                    reqPipwave.require(['pw'], function(pw) {pw.setOpt(pwconfig);pw.startLoad();});
                                } else {
                                    _.getElementById(k).parentNode.replaceChild(a, _.getElementById(k));
                                }
                            }, 800);
                        })(document, 'script', "$sdk_url", "pw.sdk.min.js", "pw.sdk.min.js", "pwscript");
                    </script>
EOD;
        } else {
            $this->result = isset($response['message']) ? (is_array($response['message']) ? implode('; ', $response['message']) : $response['message']) : "Error occured";
        }
	}

    function get_result(){
        return $this->result;
    }
}