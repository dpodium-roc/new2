<?php
namespace Zamen\CustomPayment\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	//from etc\adminhtml\system.xml
	const API_KEY = 'payment/pipwave_payment/api_key';
	const API_SECRET = 'payment/pipwave_payment/api_secret';
	const TEST_MODE = 'payment/pipwave_payment/test_mode';
	const PROCESSING_FEE = 'payment/pipwave_payment/processing_fee';
	
	public function getApiKey()
	{
		return $this->scopeConfig->getValue(
			self::API_KEY,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}
	
	public function getApiSecret()
	{
		return $this->scopeConfig->getValue(
			self::API_SECRET,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}
	
	public function getTestMode()
	{
		return $this->scopeConfig->getValue(
			self::TEST_MODE,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}
	
	public function getProcessingFee()
	{
		return $this->scopeConfig->getValue(
			self::PROCESSING_FEE,
			\Magento\Store\Model\ScopeInterface::SCOPE_STORE
		);
	}
	
}