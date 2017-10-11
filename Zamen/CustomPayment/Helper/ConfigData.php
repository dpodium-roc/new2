<?php
namespace Zamen\CustomPayment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;


class ConfigData extends AbstractHelper
{
	//@var \Magento\Framework\App\Config\ScopeConfigInterface
	protected $scopeConfig;

	const ACTIVE		='zamen/custompayment/active';
	const API_KEY		='zamen/custompayment/api_key';
	const API_SECRET	='zamen/custompayment/api_secret';
	const TEST_MODE		='zamen/custompayment/test_mode';
	const PROCESSING_FEE='zamen/custompayment/processing_fee';

	public function __construct(Context $context){
		parent::__construct($context);
	}

	public function getActive()
	{
		return $this->scopeConfig->getValue(self::ACTIVE);
	}

	//need modification [api key should not be public?]
	public function getApiKey()
	{
		return $this->scopeConfig->getValue(self::API_KEY);
	}

	//need modification
	public function getApiSecret()
	{
		return $this->scopeConfig->getValue(self::API_SECRET);
	}

	public function getTestMode()
	{
		return $this->scopeConfig->getValue(self::TEST_MODE);
	}

	public function getProcessingFee()
	{
		return $this->scopeConfig->getValue(self::PROCESSING_FEE);
	}
}