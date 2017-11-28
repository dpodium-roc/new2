<?php
namespace pipwave\CustomPayment\Model;

use \Magento\Payment\Model\Method\AbstractMethod;
use \Magento\Framework\UrlInterface as urlBuilder;
use \pipwave\CustomPayment\Helper\UrlData as UrlHelper;

class Url extends AbstractMethod
{
    public function __construct( urlBuilder $urlBuilder, UrlHelper $UrlHelper)
    {
        $this->urlBuilder = $urlBuilder;
        $this->UrlHelper = $UrlHelper;
    }
    protected $urlBuilder;
    protected $UrlHelper;
    
    public function getUrl($test_mode){

        if ($test_mode == 'yes' || $test_mode == 1) {
            $url = $this->UrlHelper::URL_TEST;
        } else {
            $url = $this->UrlHelper::URL;
        }
        return $url;
    }
    
    public function getRenderUrl($test_mode){
        
        if ($test_mode == 'yes' || $test_mode == 1) {
                $url = $this->UrlHelper::RENDER_URL_TEST;
        } else {
            $url = $this->UrlHelper::RENDER_URL;
        }
        return $url;
    }
    
    
    public function getLoadingImageUrl($test_mode){
        
        if ($test_mode == 'yes' || $test_mode == 1) {
                $url = $this->UrlHelper::LOADING_IMAGE_URL_TEST;
        } else {
            $url = $this->UrlHelper::LOADING_IMAGE_URL;
        }
        return $url;
    }
    
    public function defaultSuccessPageUrl()
    {
        return $this->urlBuilder->getUrl($this->UrlHelper::SUCCESS_URL);
    }
    public function defaultFailPageUrl()
    {    
        $temp = $this->urlBuilder->getUrl($this->UrlHelper::FAIL_URL);
        return $temp;
    }
    public function notificationPageUrl()
    {
        return $this->urlBuilder->getUrl($this->UrlHelper::NOTIFICATION_URL);
    }
}