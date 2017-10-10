<?php
namespace Zamen\CustomPayment\Custom;

use Magento\Framework\App\ObjectManager;
use Zamen\CustomPayment\Helper\ConfigData;

class InformationNeeded
{

	protected $storeManager = $objectManager->create("\Magento\Store\Model\StoreManagerInterface");
	public function __construct(
		\Magento\Store\Model\StoreManagerInterface $storeManager= $objectManager->create("\Magento\Store\Model\StoreManagerInterface"))
	{
		$this->_storeManager = $storeManager;
	}

    //variable
    protected $pw_api_key;
    protected $pw_api_secret;
    protected $txn_id;
    protected $amt;
    protected $curr_code;
    protected $buyer_id;
    protected $buyer_email;
	function process_data()
	{
		//maybe not suitable as it is not suggested by magento dev
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$cart = $objectManager->get('\Magento\Checkout\Model\Cart');
		$customer = $objectManager->get('Magento\Customer\Model\Session');
        $order = $objectManager->get('\Magento\Sales\Model\Order');

        //need to input $context, but i dk what it is
		$adminConfig = new ConfigData();

		$this->pw_api_key = $adminConfig->getApiKey();
		$this->pw_api_secret = $adminConfig->getApiSecret();

		//dont know where to find....
		$this->txn_id = 'REF001';

		$this->amt = $cart->getQuote()->getGrandTotal();
		$this->curr_code = $this->_storeManager->getStore()->
					getCurrentCurrency->getCode();

		$this->buyer_id = $customer->getCustomerId();
		$this->buyer_email = $customer->getCustomer()->getEmail();


	}


    protected $data;
	function set_data()
	{
		$this->data = array(

		//need modification, call object manager?
		//read some_functions_get_information.php [deskstop]
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

	protected $signatureParam;
	function set_signature_param($data)
	{
		//need modification, call object manager?
		//read some_functions_get_information.php [deskstop]
		$this->signatureParam = array(
			'api_key' => $data['api_key'],
			'api_secret' => $data['api_secret'],
			'txn_id' => $data['txn_id'],
			'amount' => $data['amount'],
			'currency_code' => $data['currency_code'],
			'action' => $data['action'],
			'timestamp' => $data['timestamp']
		);
	}


	function get_data()
	{
		return $this->data;
	}
	function get_signature_param()
	{
		return $this->signatureParam;
	}

}