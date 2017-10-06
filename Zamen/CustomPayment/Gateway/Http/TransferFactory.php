<?php
namespace Zamen\CustomPayment\Gateway\Http;

use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Zamen\CustomPayment\Gateway\Request\MockDataRequest;

class TransferFactory implements TransferFactoryInterface
{
	//@var TransferBuilder
	private $transferBuilder;
	
	//@param TransferBuilder $transferBuilder
	public function __construct(TransferBuilder $transferBuilder)
	{
		$this->transferBuilder = $transferBuilder;
	}
	
	/*
	 *build gateway transfer object
	 *
	 *@param array $request
	 *@return TransferInterface
	 */
	public function create(array $request)
	{
		return $this->transferBuilder
					->setBody($request)
					->setMethod('POST')
					->setHeaders(
						[
							'force_result' => isset($request[MockDataRequest::FORCE_RESULT])
								? $request[MockDataRequest::FORCE_RESULT]
								: null
						]
					)
					->build();
	}
}