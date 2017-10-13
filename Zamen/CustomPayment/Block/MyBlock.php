<?php
namespace Zamen\CustomPayment\Block;

use Zamen\CustomPayment\Custom\SetInfo;
use \Magento\Framework\App\ObjectManager;

class MyBlock extends \Magento\Framework\View\Element\Template
{
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        array $data = [],
		SetInfo $info
    )
    {        
        parent::__construct($context, $data, $info);
    }

    protected $info;
	
	public function getHelloWorldTxt()
    {
		
		return 'hey';
    }
	
	//experiment(), get_experiment() can be removed
	protected $exresult;
	public function experiment()
	{

		$this->exresult = 'well done';
	}
	public function get_experiment()
	{
		return $this->exresult;
	}
	
	
	
	//trying
	protected $pw_api_key;
    protected $pw_api_secret;
	function process_admin_data()
{
	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	$helper = $objectManager->create('\Zamen\CustomPayment\Helper\AdminInput');
	$this->pw_api_key = $helper->getCustomPaymentConfig('api_key');
	$this->pw_api_secret = $helper->getCustomPaymentConfig('api_secret');

}
	function get_admin_api_key()
	{
		return $this->pw_api_key;
	}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}