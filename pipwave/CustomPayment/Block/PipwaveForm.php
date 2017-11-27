<?php
namespace pipwave\CustomPayment\Block;

class PipwaveForm extends \Magento\Payment\Block\Form
{
    protected function _construct()
    {
        $this->setTemplate('pipwave/CustomPayment/view/frontend/templates/pipwave.phtml');
		parent::_construct();
    }
}