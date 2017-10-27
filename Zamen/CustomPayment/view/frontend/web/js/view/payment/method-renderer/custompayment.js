define(
	[
		'jquery',
		'Magento_Checkout/js/view/payment/default',
		'ko'
	],
	function ($, Component, ko)
	{
		'use strict';
		
		return Component.extend(
		{
			defaults: {
				template: 'Zamen_CustomPayment/payment/custompayment'
			},
			
			getPipwaveImageSrc: function () {
            return window.checkoutConfig.payment.pipwave.pipwaveImageSrc;
			}
		});
	}
);
