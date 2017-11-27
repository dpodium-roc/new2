<?php
namespace pipwave\CustomPayment\Controller\Index;

use \Magento\Sales\Model\Order as order;
use \Magento\Checkout\Model\Session as checkout;
use \pipwave\CustomPayment\Block\InformationNeeded as information;
use \pipwave\CustomPayment\Model\PipwaveIntegration as pipwaveIntegration;

class Complete extends \Magento\Framework\App\Action\Action
{
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		checkout $checkout,
		information $information,
		pipwaveIntegration $pipwaveIntegration,
		order $order)
	{
		$this->order = $order;
		$this->checkout = $checkout;
		$this->information = $information;
		$this->pipwaveIntegration = $pipwaveIntegration;
		parent::__construct($context);
	}
	
	protected $checkout;
	protected $order;
	protected $information;
	protected $pipwaveIntegration;

	
    public function execute()
    {
        //$request = $this->getRequest();
        //EXECUTE YOUR LOGIC HERE
		//die('allaalal');
		header('HTTP/1.1 200 OK');
		echo "OK";
		$post_data = json_decode(file_get_contents('php://input'), true);
		
		//IPN from pipwave
		$timestamp = (isset($post_data['timestamp']) && !empty($post_data['timestamp'])) ? $post_data['timestamp'] : time();
		$pw_id = (isset($post_data['pw_id']) && !empty($post_data['pw_id'])) ? $post_data['pw_id'] : '';
		$order_number = (isset($post_data['txn_id']) && !empty($post_data['txn_id'])) ? $post_data['txn_id'] : '';
		$amount = (isset($post_data['amount']) && !empty($post_data['amount'])) ? $post_data['amount'] : '';
		$final_amount = (isset($post_data['final_amount']) && !empty($post_data['final_amount'])) ? $post_data['final_amount'] : 0.00;
		$currency_code = (isset($post_data['currency_code']) && !empty($post_data['currency_code'])) ? $post_data['currency_code'] : '';
		$transaction_status = (isset($post_data['transaction_status']) && !empty($post_data['transaction_status'])) ? $post_data['transaction_status'] : '';
		$payment_method = 'pipwave' . (!empty($post_data['payment_method_title']) ? (" - " . $post_data['payment_method_title']) : "");
		$signature = (isset($post_data['signature']) && !empty($post_data['signature'])) ? $post_data['signature'] : '';
		//$shipping = (isset($post_data['shipping_info']) && !empty($post_data['shipping_info'])) ? $post_data['shipping_info'] : '';
		
		
		// pipwave risk execution result
		$pipwave_score = isset($post_data['pipwave_score']) ? $post_data['pipwave_score'] : '';
		$rule_action = isset($post_data['rules_action']) ? $post_data['rules_action'] : '';
		$message = isset($post_data['message']) ? $post_data['message'] : '';
		$signatureParam = array(
			'timestamp' => $timestamp,
			'pw_id' => $pw_id,
			'txn_id' => $order_number,
			'amount' => $amount,
			'currency_code' => $currency_code,
			'transaction_status' => $transaction_status,
		);
		
		$newSignature = $this->pipwaveIntegration->generate_pw_signature($signatureParam);
		
		//check signature
		if ($signature != $newSignature) {
                $transaction_status = -1;
            }
		
		//change order status
		//$orderId = $this->information->getOrderId();
		$order = $this->order->load($order_number);
		$state = $this->information->getOrderStatus($transaction_status);
		
		$order->setState($state)->setStatus($state);
		//$order->addStatusHistoryComment(__('status detail: '.$state.'Thank you.'));
		//$order->addComment('paid with: '+$payment_method);
		//$order->setShippingAddress();
		$order->save();
		
		//echo 'efg';
		
		

    }
}