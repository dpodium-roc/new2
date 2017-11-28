<?php
namespace pipwave\CustomPayment\Model;

class NotificationInformation extends \Magento\Framework\Model\AbstractModel
{
    public function _construct()
    {
        //parent::_construct();
        $this->_init('pipwave\CustomPayment\Model\ResourceModel\NotificationInformation');
    }
}