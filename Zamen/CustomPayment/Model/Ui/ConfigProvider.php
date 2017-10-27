<?php
namespace Zamen\CustomPayment\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

use Zamen\CustomPayment\Gateway\Http\Client\ClientMock;

final class ConfigProvider implements ConfigProviderInterface
{
	const CODE = 'zamen_custompayment';
	
	public function getConfig()
	{
		return
		[
			'payment' =>
			[
				self::CODE =>
				[
					'transactionResults' =>
					[
						ClientMock::SUCCESS => __('Success'),
						ClientMock::FAILURE => __('Fraud')
					]
				]
			]
		];
	}
}