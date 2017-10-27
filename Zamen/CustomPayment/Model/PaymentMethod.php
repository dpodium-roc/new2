<?php
namespace Zamen\CustomPayment\Model;

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
	//type payment method code here
	protected $_code='custompayment';
	
	//experiment
	//own form
	protected $_formBlockType = 'Zamen\CustomPayment\Block\PipwaveForm';
	
	//run all
	protected $_isOffline = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
	
	
}