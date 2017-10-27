<?php
namespace Zamen\CustomPayment\Block;

class HelloWorld extends \Magento\Framework\View\Element\Template
{        
	
	
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {  
        parent::__construct($context, $data);
    }
    
    public function getHelloWorld()
    {
        return 'Hello World';
    }
    
	protected $objectManager;
	public function initObjectManager()
	{
		$this->objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	}
	// to getApiKey, getApiSecret, getTestMode, getProcessingFee
	// admin config data
	public function getAdminData()
	{
		return $this->objectManager->get('Zamen\CustomPayment\Helper\Data');
	}	
	
	public function getCustomerData()
	{
		return $this->objectManager->get('Magento\Customer\Model\Session');
	}
	
	public function getUrlLink()
	{
		return $this->objectManager->get('Zamen\CustomPayment\Model\Method');
	}
	////////////////////////////////////////////////////////////////
	
	protected $data;
	protected $signature_param;
	protected $url;
	public function do_all()
	{
		$information = $this->objectManager->get('Zamen\CustomPayment\Block\InformationNeeded');
		$information->get_manager();
		
		$information->set_data();
		$this->data = $information->get_data();
		
		var_dump($this->data);
		
		$information->set_signature_param();
		$information->insert_signature();
		
		$this->data = $information->get_data();
		echo '<br>data with signature';
		var_dump($this->data);
		
		$this->url = $information->get_url();
		echo '<br>var dump url';
		var_dump($this->url);
		
		echo 'i used the sendRequest() here and get this';
		$information->sendRequest();
		$information->render();
		
		//from sendRequest()
		$temp = $information->get_response();
		var_dump($temp);
		
		//from render()
		$temp = $information->get_result();
		var_dump($temp);
		
		
	}
	
	public function send_request()
	{
		$information = $this->objectManager->get('Zamen\CustomPayment\Model\PipwaveIntegration');
		$information->get_manager();
		
		$agent = $ful->get_agent();
		
		return $ful->send_request_to_pw($this->data, $this->data['api_key'], $this->url, $agent);
	}
	

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
?>