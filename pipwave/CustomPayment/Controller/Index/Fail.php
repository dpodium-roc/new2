<?php

namespace pipwave\CustomPayment\Controller;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;



class Success extends \Magento\Checkout\Controller\Onepage
{
	
	public function __construct(Context $context, 
        \Magento\Checkout\Model\Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;

        parent::__construct($context);
    }
	protected $checkoutSession;

    /**
     * Order success action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		if ($this->checkoutSession->restoreQuote()) {
			$this->_redirect('checkout/cart');
			
		}
    }
}



