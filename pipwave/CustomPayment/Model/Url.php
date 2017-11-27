<?php
namespace pipwave\CustomPayment\Model;

use \Magento\Payment\Model\Method\AbstractMethod;
use \Magento\Framework\UrlInterface as urlBuilder;
use \pipwave\CustomPayment\Model\Config as config;

class Url extends AbstractMethod
{
	public function __construct( urlBuilder $urlBuilder, config $config)
	{
		$this->urlBuilder = $urlBuilder;
		$this->config = $config;
	}
	protected $urlBuilder;
	protected $config;
	
    public function getUrl($test_mode){

        if ($test_mode == 'yes' || $test_mode == 1) {
            $url = $this->config::URL_TEST;
        } else {
            $url = $this->config::URL;
        }
        return $url;
    }
	
	public function getRenderUrl($test_mode){
		
		if ($test_mode == 'yes' || $test_mode == 1) {
				$url = $this->config::RENDER_URL_TEST;
		} else {
			$url = $this->config::RENDER_URL;
		}
		return $url;
	}
	
	
	public function getLoadingImageUrl($test_mode){
		
		if ($test_mode == 'yes' || $test_mode == 1) {
				$url = $this->config::LOADING_IMAGE_URL_TEST;
		} else {
			$url = $this->config::LOADING_IMAGE_URL;
		}
		return $url;
	}
	
	public function defaultSuccessPageUrl()
	{
		return $this->urlBuilder->getUrl($this->config::SUCCESS_URL);
	}
	public function defaultFailPageUrl()
	{	
		$temp = $this->urlBuilder->getUrl($this->config::FAIL_URL);
		return $temp;
	}
	public function notificationPageUrl()
	{
		return $this->urlBuilder->getUrl($this->config::NOTIFICATION_URL);
	}
}