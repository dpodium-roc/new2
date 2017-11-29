<?php
namespace pipwave\CustomPayment\Model\ResourceModel\NotificationInformation;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'pipwave\CustomPayment\Model\NotificationInformation',
            'pipwave\CustomPayment\Model\ResourceModel\NotificationInformation'
            );
    }
}