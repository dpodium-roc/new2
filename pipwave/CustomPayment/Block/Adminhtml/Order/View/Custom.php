<?php
namespace pipwave\CustomPayment\Block\Adminhtml\Order\View;
class Custom extends \Magento\Backend\Block\Template
{
	/*
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {  
        parent::__construct($context, $data);
    }
	*/
	protected $_template = 'order/view/custom.phtml';
	/*
	public function __construct(\Magento\Framework\View\Element\Template\Context $context)
	{
		parent::__construct($context);
	}
	*/
	
	public function sayHello()
	{
		return __('Hello World');
	}
	
}