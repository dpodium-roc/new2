<?php
namespace pipwave\CustomPayment\Block;

use \Magento\Framework\UrlInterface as urlBuilder;

class PipForm extends \Magento\Framework\View\Element\Template
{        
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
		urlBuilder $urlBuilder,
        array $data = []
    )
    {  
		$this->urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }
	
	protected $urlBuilder;
	public function mainn()
    {
        echo 'Hello World';
    }
	
	public function completePageUrl()
	{
		return $this->urlBuilder->getUrl('complete/index/complete');
	}
	
	public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

}