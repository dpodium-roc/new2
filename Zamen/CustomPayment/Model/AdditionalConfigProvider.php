<?php
namespace Zamen\CustomPayment\Model;

class AdditionalConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
	public function getConfig()
	{
		//this is going to be called in view\frontend\web\js\view\payment\method-renderer\custompayment.js
		$config =
		[
			'payment' => 
			[
				'pipwave' =>
				[
					//this need changes if possible.
					//set $image_url = *something*
					//then use magento framework to get url
					//set $image_url into 'pipwaveImageSrc'
					'pipwaveImageSrc' => 'https://www.pipwave.com/wp-content/themes/zerif-lite-child/images/logo_bnw.png'
				]
			]
		];
		
		return $config;
	}
}