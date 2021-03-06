<?php
namespace Zamen\CustomPayment\Custom;

use Magento\Framework\App\ObjectManager;
use Zamen\CustomPayment\Helper\AdminInput;


class SetInfo
{

    protected $helper = $this->objectManager->create('Zamen\CustomPayment\Helper\AdminInput');
    protected $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

	protected $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
	protected $customer = $objectManager->get('Magento\Customer\Model\Session');
    protected $order = $objectManager->get('\Magento\Sales\Model\Order');

    //variable
    protected $pw_api_key;
    protected $pw_api_secret;
    protected $txn_id;
    protected $amt;
    protected $curr_code;
    protected $buyer_id;
    protected $buyer_email;

    public function __construct(){

        //?
        $this->pw_api_key = $this->helper->getCustomPaymentConfig('api_key');
		$this->pw_api_secret = $this->helper->getCustomPaymentConfig('api_secret');

		//dont know where to find....
		$this->txn_id = 'REF001';

		$this->amt = $this->cart->getQuote()->getGrandTotal();
		$this->curr_code = $this->_storeManager->getStore()->getCurrentCurrency->getCode();

		$this->buyer_id = $this->customer->getCustomerId();
		$this->buyer_email = $this->customer->getCustomer()->getEmail();


	}

    protected $data;
    public function set_data()
    {
        $this->data = array(
            'action' => 'initiate-payment',
			'timestamp' => time(),
			'api_key' => $this->pw_api_key,
            'api_secret' => $this->pw_api_secret,
			'txn_id' => $this->txn_id,
			'amount' => $this->amt,
			'currency_code' => $this->curr_code,
			'buyer_info' => array(
				'id' => $this->buyer_id,
				'email' => $this->buyer_email,
            ),
            );

    }
    function get_data()
	{
		return $this->data;
	}

    protected $signatureParam;
	function set_signature_param()
	{
		//need modification, call object manager?
		//read some_functions_get_information.php [deskstop]
		$this->signatureParam = array(
			'api_key' => $this->data['api_key'],
			'api_secret' => $this->data['api_secret'],
			'txn_id' => $this->data['txn_id'],
			'amount' => $this->data['amount'],
			'currency_code' => $this->data['currency_code'],
			'action' => $this->data['action'],
			'timestamp' => $this->data['timestamp'],
		);
	}
    function get_signature_param()
	{
		return $this->signatureParam;
	}




}