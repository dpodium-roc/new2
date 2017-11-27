<?php

namespace pipwave\CustomPayment\Controller\Fail;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;



class Index extends \Magento\Framework\App\Action\Action
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



