<?php
namespace Zamen\CustomPayment\Helper;

class ConfigData extends \Magento\Framework\App\Helper\AbstractHelper
{
	//@var \Magento\Framework\App\Config\ScopeConfigInterface
	protected $_scopeConfig;
	
	const ACTIVE		='zamen/custompayment/active';
	const API_KEY		='zamen/custompayment/api_key';
	const API_SECRET	='zamen/custompayment/api_secret';
	const TEST_MODE		='zamen/custompayment/test_mode';
	const PROCESSING_FEE='zamen/custompayment/processing_fee';
	
	public function __construct
	(
		\Magento\Framework\App\Helper\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	){
		parent::__construct($context);
		$this->_scopeConfig = $scopeConfig;
	}
	
	public function getActive()
	{
		return $this->_scopeConfig->getValue(self::ACTIVE);
	}
	
	//need modification [api key should not be public?]
	public function getApiKey()
	{
		return $this->_scopeConfig->getValue(self::API_KEY);
	}
	
	//need modification
	public function getApiSecret()
	{
		return $this->_scopeConfig->getValue(self::API_SECRET);
	}
	
	public function getTestMode()
	{
		return $this->_scopeConfig->getValue(self::TEST_MODE);
	}
	
	public function getProcessingFee()
	{
		return $this->_scopeConfig->getValue(self::PROCESSING_FEE);
	}
}