<?php
namespace Zamen\CustomPayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

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
						'enter here'
					]
				]
			]
		];
	}
}