<?php
namespace Zamen\CustomPayment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class TestObserver
 */
class TestObserver implements ObserverInterface
{

	protected $_responseFactory;
    protected $_url;
	
	 public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
		/*
        $event = $observer->getEvent();
        $CustomRedirectionUrl = $this->_url->getUrl('Zamen/CustomPayment/Controller/Index/Index');
        $this->_responseFactory->create()->setRedirect($CustomRedirectionUrl)->sendResponse();
		return $this;
		
		*/
		die('hei, good bafd');
    }
}