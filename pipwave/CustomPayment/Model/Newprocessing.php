<?php
namespace pipwave\CustomPayment\Model;

//overwrite 
class Newprocessing extends \Magento\Sales\Model\Config\Source\Order\Status\Newprocessing
{
	protected $_stateStatuses = [
        \Magento\Sales\Model\Order::STATE_NEW,
        \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT,
    ];
}