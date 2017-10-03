<?php
namespace Zamen\CustomPayment\Model;

class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
	//type payment method code here
	protected $_code='custompayment';
	
	//experiment
	
	//run all
	
	function all()
	{
		//check availability
		//--WRITE HERE----
		
		echo 'no';
		//load language??
		
	}
	all();
	
	
	
}