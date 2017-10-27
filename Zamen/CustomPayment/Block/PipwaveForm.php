<?php
namespace Zamen\CustomPayment\Block;

class PipwaveForm extends \Magento\Payment\Block\Form
{
    protected function _construct()
    {
        $this->setTemplate('Zamen/CustomPayment/view/frontend/templates/pipwave.phtml');
		parent::_construct();
    }
}