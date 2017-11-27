<?php

namespace pipwave\CustomPayment\Controller\Success;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\OrderFactory;



class Index extends \Magento\Framework\App\Action\Action
{
	
	public function __construct(Context $context, 
        \Magento\Sales\Model\Order $order)
    {
        $this->order = $order;

        parent::__construct($context);
    }
	protected $order;

    /**
     * Order success action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
		header('HTTP/1.1 200 OK');
		echo "OK";
		$post_data = json_decode(file_get_contents('php://input'), true);
		
		$order_number = (isset($post_data['txn_id']) && !empty($post_data['txn_id'])) ? $post_data['txn_id'] : '';
		$amount = (isset($post_data['amount']) && !empty($post_data['amount'])) ? $post_data['amount'] : '';
		
				
		$order = $this->order->load($order_number);
		$order->setState(order::STATE_PROCESSING)->setStatus(order::STATE_PROCESSING);
		$order->save();
		$this->_redirect('checkout/onepage/success');
		
	
    }
}



