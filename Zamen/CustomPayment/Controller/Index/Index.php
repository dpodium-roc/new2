<?php
namespace Zamen\CustomPayment\Controller\Index;

class Index extends \Zamen\CustomPayment\Controller\Index
{
/**
* Show Hello World page
*
* @return void
*/
public function execute()
{
$this->_view->loadLayout();
$this->_view->renderLayout();
}
}