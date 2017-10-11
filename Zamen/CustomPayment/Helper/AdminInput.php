<?php
namespace Zamen\CustomPayment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;



//this is to get value from adminhtml
class AdminInput extends AbstractHelper
{
    protected $storeManager;
    protected $objectManager;

    const XML_PATH_ZAMEN_CUSTOMPAYMENT = 'zamen/custompayment/';

	public function __construct
	(
		Context $context, ObjectManagerInterface $objectManager, StoreManagerInterface $storeManager
    ){
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
		parent::__construct($context);
	}

	//set function for getCustomPaymentConfig
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    //get custompayment config
    public function getCustomPaymentConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_ZAMEN_CUSTOMPAYMENT.$code, $storeId);
    }

    /*
     * to get value
     * use this
     *
     * $helper = $this->objectManager->create('Zamen\CustomPayment\Helper\AdminInput');
     * echo $helper->getCustomPaymentConfig('active');
     * echo $helper->getCustomPaymentConfig('api_key');
     * echo $helper->getCustomPaymentConfig('api_secret');
     * echo $helper->getCustomPaymentConfig('test_mode');
     * echo $helper->getCustomPaymentConfig('processing_fee');
     */
}