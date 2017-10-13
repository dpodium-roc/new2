<?php
namespace Zamen\CustomPayment\Block;

use Magento\Framework\Phrase;
use Magento\Payment\Block\ConfigurableInfo;
use Zamen\CustomPayment\Gateway\Response\FraudHandler;



class Info extends ConfigurableInfo
{
	/*
	 *@param string $field
	 *return phrase? label?
	 */
	protected function getLabel($field)
	{
		return __($field);
	}
	
	/*
	 *@param string $field
	 *@param string $value
	 *return phrase? value view?
	 */
	protected function getValueView($field, $value)
	{
		switch ($field){
			case FraudHandler::FRAUD_MSG_LIST:
				return implode('; ', $value);
		}
		return parent::getValueView($field, $value);
	}
}