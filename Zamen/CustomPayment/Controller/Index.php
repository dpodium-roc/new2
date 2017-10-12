<?php
namespace Zamen\CustomPayment\Controller;

use Magento\Framework\App\Action\NotFoundException;
use Magento\Framework\App\RequestInterface;

class Index extends \Magento\Framework\App\Action\Action
{

public function __construct(\Magento\Framework\App\Action\Context $context)
{
	parent::__construct($context);
}

public function execute(){}
}