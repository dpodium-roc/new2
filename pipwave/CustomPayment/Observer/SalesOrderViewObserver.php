<?php
namespace pipwave\CustomPayment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Controller\ResultFactory;

use pipwave\CustomPayment\Block\InformationNeeded as Information;

/**
 * Class TestObserver
 */
class SalesOrderViewObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $block = $observer->getBlock();
        
        if(($block->getNameInLayout() == 'order_info') && ($child = $block->getChild('salesOrderCustomBlock'))){
            $transport = $observer->getTransport();
            if($transport){
                $html = $transport->getHtml();
                $html .= $child->toHtml();
                $transport->setHtml($html);
            }
        }
    }
}