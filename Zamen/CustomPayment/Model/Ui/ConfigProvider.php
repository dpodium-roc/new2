<?php
namespace Zamen\CustomPayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Zamen\CustomPayment\Gateway\Http\Client\ClientMock;

//class ConfigProvider
final class ConfigProvider implements ConfigProviderInterface
{
	const CODE = 'sample_gateway';
	
	//retrieve assoc array of checkout config
	//@return array
	public function getConfig()
	{
		return [
			'payment' => [
				self::CODE => [
					'transactionResults' => [
						ClientMock::SUCCESS => __('Success'),
						ClientMock::FAILURE => __('Fraud')
					]
				]
			]
		];
	}
}