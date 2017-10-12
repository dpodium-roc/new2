<?php
 namespace Zamen\CustomPayment\Controller\Index;
 
 class Index extends \Magento\Framework\App\Action\Action
 {
   public function execute()
   {
      $hello = new \Magento\Framework\DataObject(array('label' => 'Hello'));
      $this->_eventManager->dispatch('custom_payment_display', ['display' => $hello]);
      echo $hello->getDisplay();
      
   }
 }