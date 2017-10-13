<?php
namespace Zamen\CustomPayment\Controller\Index;

use Magento\Framework\App\Action\Context;

class Index extends \Zamen\CustomPayment\Controller\Index
{
	
	protected $resultPageFactory;

	public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
        )
        {
            $this->resultPageFactory = $resultPageFactory;
            parent::__construct($context);
        }
		
	public function execute()
    {
        return $this->resultPageFactory->create();
    }
}


