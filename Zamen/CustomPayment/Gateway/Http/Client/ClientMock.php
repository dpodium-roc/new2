<?php
namespace Zamen\CustomPayment\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

class ClientMock implements ClientInterface
{
	const SUCCESS = 1;
	const FAILURE = 0;
	
	//@var array
	private $results = 
	[
		self::SUCCESS,
		self::FAILURE
	];
	
	//@var Logger
	private $logger;
	
	//@param Logger $logger
	public function __construct(Logger $logger)
	{
		$this->logger = $logger;
	}
	
	/*
	 *place request to gateway. return result as ENV array
	 *
	 *@param TransferInterface $transferObject
	 *@return array
	 */
	public function placeRequest(TransferInterface $transferObject)
	{
		$reponse = $this->generateResponseForCode
		(
			$this->getResultCode($transferObject)
		);
			
		$this->logger->debug
		(
			[
				'request' => $transferObject->getBody(),
				'response' => $response
			]
		);
		return $reponse;
	}
	
	/*
	 *generate response
	 *
	 *@return array
	 */
	protected function generateResponseForCode($resultCode)
	{
		return array_merge
		(
			[
				'RESULT_CODE' => $resultCode,
				'TXN_ID' => $this-> generateTxnId()
			],
			$this->getFieldsBasedOnResponseType($resultCode)
		);
	}
	
	
	//@return string
	private function generateTxnId()
	{
		return md5(mt_rand(0, 1000));
	}
	
	private function getResultCode(TransferInterface $transfer)
	{
		$headers = $transfer->getHeaders();
		
		if (isset($headers['force_result']))
		{
			return (int)$headers['force_result'];
		}
		return $this->results[mt_rand(0, 1)];
	}
	
	/*
	 *return response fields for result code
	 *
	 *@param int $resultCode
	 *@return array
	 */
	private function getFieldsBasedOnResponseType($resultCode)
	{
		switch ($resultCode)
		{
			case self::FAILLURE:
				return 
				[
					'FRAUD_MSG_LIST' => 
					[
						'Stolen card', 'Customer location differs'
					]
				];
		}
		return [];
	}
}