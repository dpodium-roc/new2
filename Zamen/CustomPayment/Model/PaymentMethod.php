<?php
namespace Zamen\CustomPayment\Model;

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
	//type payment method code here
	protected $_code='custompayment';
	
}