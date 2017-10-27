<?php
namespace Zamen\CustomPayment\Block;

use \Magento\Store\Model\StoreManagerInterface;

class InformationNeeded extends \Magento\Framework\View\Element\Template
{	

	protected $cart;
	protected $customer;
	protected $order;
	protected $adminConfig;
	protected $urlLink;
	protected $pipwaveIntegration;
	
	function get_manager()
	{
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		
		
		$this->cart = $objectManager->get('\Magento\Checkout\Model\Cart');
		$this->customer = $objectManager->get('Magento\Customer\Model\Session');
        $this->order = $objectManager->get('\Magento\Sales\Model\Order');
		
		$this->adminConfig = $objectManager->get('Zamen\CustomPayment\Helper\Data');
		$this->urlLink = $objectManager->get('Zamen\CustomPayment\Model\Method');
		
		$this->pipwaveIntegration = $objectManager->get('Zamen\CustomPayment\Model\PipwaveIntegration');
	}
	
    protected $data;
	protected $url;
	protected $renderUrl;
	protected $loadingImageUrl;
	
	function set_data()
	{
		$temp = $this->cart->getQuote()->getGrandTotal();
		$this->data = array(

		//need modification, call object manager?
		//read some_functions_get_information.php [deskstop]
			'action' => 'initiate-payment',
			'timestamp' => time(),
			'api_key' => $this->adminConfig->getApiKey(),
            'api_secret' => 'UpZxmVLeKKPOVjPMyUDp10GapjxucR4PaUAqK0uO',//$this->adminConfig->getApiSecret(),
			'txn_id' => 'REF001', //here got error......................................(ned to get txn_id)
			'amount' => round($temp, 2),
			'currency_code' => 'USD',//here got error......................................(ned to get currency_code)
			'buyer_info' => array(
				'id' => $this->customer->getCustomerId(),
				'email' => $this->customer->getCustomer()->getEmail(),
				'processing_fee_group' => $this->adminConfig->getProcessingFee(),
			),
		);
		
		$this->url = $this->urlLink->getUrl($this->adminConfig->getTestMode());
		$this->renderUrl = $this->urlLink->getRenderUrl($this->adminConfig->getTestMode());
		$this->loadingImageUrl = $this->urlLink->getLoadingImageUrl($this->adminConfig->getTestMode());
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
			'timestamp' => $this->data['timestamp']
		);
	}
	
	// insert signature into data['signature']
	function insert_signature()
	{
		$this->data['signature'] = $this->pipwaveIntegration->generate_pw_signature($this->signatureParam);
	}
	
	protected $response;
	function sendRequest()
	{
		$agent = $this->pipwaveIntegration->get_agent();
		$this->response = $this->pipwaveIntegration->send_request_to_pw($this->data, $this->data['api_key'], $this->url, $agent);
	}
	
	protected $result;
	function render()
	{
		$this->result = $this->pipwaveIntegration->render_sdk($this->response, $this->response['token'], $this->data['api_key'], $this->renderUrl, $this->loadingImageUrl);
	}


	function get_data()
	{
		return $this->data;
	}
	function get_signature_param()
	{
		return $this->signatureParam;
	}
	function get_url()
	{
		return $this->url;
	}
	
	function get_response()
	{
		return $this->response;
	}
	
	function get_result()
	{
		return $this->result;
	}
	
	

}