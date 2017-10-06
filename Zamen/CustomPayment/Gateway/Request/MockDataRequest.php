<?php
namespace Zamen\CustomPayment\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Zamen\CustomPayment\Gateway\Http\Client\ClientMock;

class MockDataRequest implements BuilderInterface
{
	const FORCE_RESULT = 'FORCE_RESULT';
	
	/*
	 *build ENV request
	 *
	 *@param array $buildSubject
	 *@return array
	 */
	public function build(array $buildSubject)
	{
		if (!isset($buildSubject['payment'])
			|| !$buildSubject['payment']
			instanceof PaymentDataObjectInterface
		){
			throw new \InvalidArgumentExeption('Payment data object should be provided');
		}
		
		//@var PaymentDataObjectInterface $paymentDO
		$paymentDO = $buildSubject['payment'];
		$payment = $paymentDO->getPayment();
		
		$transactionResult = $payment->getAdditionalInformation
									('transantion_result');
		
		return
		[
			self::FORCE_RESULT => $transactionResult === null
				? ClientMock::SUCCESS
				: $transactionResult
		];
	}
}