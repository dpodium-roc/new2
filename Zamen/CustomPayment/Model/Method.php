<?php
namespace Zamen\CustomPayment\Model;

use Magento\Payment\Model\Method\AbstractMethod;

class Method extends AbstractMethod
{
	
    const URL = 'https://api.pipwave.com/payment';
    const URL_TEST = 'https://staging-api.pipwave.com/payment';

    public function getUrl($test_mode){

        if ($test_mode == 'yes' || $test_mode == 1) {
            $url = self::URL_TEST;
        } else {
            $url = self::URL;
        }
        return $url;
    }
	
	const RENDER_URL = 'https://secure.pipwave.com/sdk/';
    const RENDER_URL_TEST = 'https://staging-checkout.pipwave.com/sdk/';
	public function getRenderUrl($test_mode){
		
		if ($test_mode == 'yes' || $test_mode == 1) {
				$url = self::RENDER_URL_TEST;
		} else {
			$url = self::RENDER_URL;
		}
		return $url;
	}
	
	const LOADING_IMAGE_URL = 'https://secure.pipwave.com/images/loading.gif';
    const LOADING_IMAGE_URL_TEST = 'https://staging-checkout.pipwave.com/images/loading.gif';
	public function getLoadingImageUrl($test_mode){
		
		if ($test_mode == 'yes' || $test_mode == 1) {
				$url = self::LOADING_IMAGE_URL_TEST;
		} else {
			$url = self::LOADING_IMAGE_URL;
		}
		return $url;
	}
	
	
}