<?php
namespace pipwave\CustomPayment\Controller\Notification;

use \Magento\Sales\Model\Order as order;
use \Magento\Checkout\Model\Session as checkout;
use \pipwave\CustomPayment\Block\InformationNeeded as information;
use \pipwave\CustomPayment\Model\PipwaveIntegration as pipwaveIntegration;

class Index extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        checkout $checkout,
        information $information,
        pipwaveIntegration $pipwaveIntegration,
        \pipwave\CustomPayment\Model\ResourceModel\NotificationInformationFactory $NotificationInformationFactoryDB,
        \pipwave\CustomPayment\Model\NotificationInformationFactory $NotificationInformationFactory,
        order $order)
    {
        $this->order = $order;
        $this->checkout = $checkout;
        $this->information = $information;
        $this->pipwaveIntegration = $pipwaveIntegration;
        $this->NotificationInformationFactoryDB = $NotificationInformationFactoryDB;
        $this->NotificationInformationFactory = $NotificationInformationFactory;
        parent::__construct($context);
    }
    
    protected $checkout;
    protected $order;
    protected $information;
    protected $pipwaveIntegration;
    protected $NotificationInformationFactoryDB;
    protected $NotificationInformationFactory;

    
    public function execute()
    {
        //$request = $this->getRequest();
        //EXECUTE YOUR LOGIC HERE
        //die('allaalal');
        header('HTTP/1.1 200 OK');
        echo "OK";
        $post_data = json_decode(file_get_contents('php://input'), true);
        
        //IPN from pipwave
        $timestamp = (isset($post_data['timestamp']) && !empty($post_data['timestamp'])) ? $post_data['timestamp'] : time();
        $pw_id = (isset($post_data['pw_id']) && !empty($post_data['pw_id'])) ? $post_data['pw_id'] : '';
        $order_number = (isset($post_data['txn_id']) && !empty($post_data['txn_id'])) ? $post_data['txn_id'] : '';
        $amount = (isset($post_data['amount']) && !empty($post_data['amount'])) ? $post_data['amount'] : '';
        $total_amount = (isset($post_data['total_amount']) && !empty($post_data['total_amount'])) ? $post_data['total_amount'] : 0.00;
        $final_amount = (isset($post_data['final_amount']) && !empty($post_data['final_amount'])) ? $post_data['final_amount'] : 0.00;
        $currency_code = (isset($post_data['currency_code']) && !empty($post_data['currency_code'])) ? $post_data['currency_code'] : '';
        $transaction_status = (isset($post_data['transaction_status']) && !empty($post_data['transaction_status'])) ? $post_data['transaction_status'] : '';
        $payment_method = 'pipwave' . (!empty($post_data['payment_method_title']) ? (" - " . $post_data['payment_method_title']) : "");
        $signature = (isset($post_data['signature']) && !empty($post_data['signature'])) ? $post_data['signature'] : '';
        //$shipping = (isset($post_data['shipping_info']) && !empty($post_data['shipping_info'])) ? $post_data['shipping_info'] : '';
        $refund = $total_amount - $final_amount;
        //var_dump($post_data);
        
        // pipwave risk execution result
        $pipwave_score = isset($post_data['pipwave_score']) ? $post_data['pipwave_score'] : '';
        $rule_action = isset($post_data['rules_action']) ? $post_data['rules_action'] : '';
        $message = isset($post_data['message']) ? $post_data['message'] : '';
        $signatureParam = array(
            'timestamp' => $timestamp,
            'pw_id' => $pw_id,
            'txn_id' => $order_number,
            'amount' => $amount,
            'currency_code' => $currency_code,
            'transaction_status' => $transaction_status,
        );
        
        $newSignature = $this->pipwaveIntegration->generate_pw_signature($signatureParam);
        // remember DO NOT DELETE check signature
        /*
        //check signature
        if ($signature != $newSignature) {
                $transaction_status = -1;
            }
        */
        
        
        //get order using increment id
        $order = $this->order->loadByIncrementId($order_number);
        
        //testing status other than 10
        //$transaction_status = 20;
        
        
        $order = $this->information->processNotification($transaction_status, $order, $refund);
        
        $order->addStatusHistoryComment('Rule Action: '.$rule_action)->setIsCustomerNotified(false);
        if ($pipwave_score!='') {
            $order->addStatusHistoryComment('pipwave Score: '.$pipwave_score)->setIsCustomerNotified(false);
        }
        $order->save();
        /*
        $NotificationInformationModel = $this->NotificationInformationFactory->create([
            'order_id' => '345',
            'pw_id' => 'dfrtg',
            ]);
        $NotificationInformationModel->save();
        */
        var_dump($post_data);
        //var_dump($post_data['mobile_number_verification']);
        //var_dump($post_data['mobile_number']);
        $data =[
            'order_id' => $order_number,
            'pw_id' => $pw_id,
            'txn_id' => $post_data['txn_id'],
            'pg_txn_id' => $post_data['pg_txn_id'],
            'amount' => $post_data['amount'],
            'tax_exempted_amount' => $post_data['tax_exempted_amount'],
            'processing_fee_amount' => $post_data['processing_fee_amount'],
            'shipping_amount' => $post_data['shipping_amount'],
            'handling_amount' => $post_data['handling_amount'],
            'tax_amount' => $post_data['tax_amount'],
            'total_amount' => $post_data['total_amount'],
            'final_amount' => $post_data['final_amount'],
            'currency_code' => $post_data['currency_code'],
            'subscription_token' => $post_data['subscription_token'],
            'charge_index' => $post_data['charge_index'],
            'payment_method_code' => $post_data['payment_method_code'],
            'payment_method_title' => $post_data['payment_method_title'],
            'reversible_payment' => $post_data['reversible_payment'],
            'settlement_account' => $post_data['settlement_account'],
            'require_capture' => $post_data['require_capture'],
            'transaction_status' => $post_data['transaction_status'],
            'mobile_number' => $post_data['mobile_number'],
            'mobile_number_country_code' => $post_data['mobile_number_country_code'],
            'mobile_number_verification' => $post_data['mobile_number_verification'],
            'risk_service_type' => $post_data['risk_service_type'],
            'aft_score' => $post_data['aft_score'],
            'aft_status' => $post_data['aft_status'],
            'pipwave_score' => $post_data['pipwave_score'],
            'rules_action' => $post_data['rules_action'],
            'risk_management_data' => json_encode($post_data['risk_management_data']),
            'matched_rules' => json_encode($post_data['matched_rules'])
            ];
        
        //$this->NotificationInformationFactory->create()->setData($data)->save();
        
        
        
        $NotificationInformationModel = $this->NotificationInformationFactory->create();
        
        //$NotificationInformationModel->load('ginnie');
        //
        $NotificationInformationModel->setData($data);
        $NotificationInformationDB = $this->NotificationInformationFactoryDB->create()->save($NotificationInformationModel);
        //var_dump($NotificationInformationDB);
        //$NotificationInformationModel->setData('order_id', 'wedrfvt')->save();
        //$NotificationInformationModel->save();
        //var_dump($NotificationInformationModel['order_id']);
        //var_dump($NotificationInformationModel);
        /*
        $text = $NotificationInformationModel->load('wedrfvt');
        var_dump($text);
        */
		
		/*
		$xxx = $order->getAllItems();
		foreach ($order->getAllItems() as $item) { 
            $data['item_info'][] = array(
                'name' => $item->getName(),
                'amount' => $item->getPrice(),
				'currency_code' => $post_data['currency_code'],
                'quantity' => $item->getQtyOrdered(),
                'sku' => $item->getSku(),
            );
        }
		*/
		/*
		foreach ($order->getAllItems() as $item) {
			$product = $item->getProduct();
			if ((float)$product->getPrice()!=0) {
            $itemInfo[] = array(
                'name' => (null!==$product->getName() && !empty($product->getName()) ? $product->getName() : ''),
                'sku' => (null!==$product->getSku() && !empty($product->getSku()) ? $product->getSku() : ''),
				'currency_code' => '',
                'amount' => (float)$product->getPrice(),//(null!==$product->getPrice() && !empty($product->getPrice()) ? $product->getPrice() : ''),
                'quantity' => (int)$item->getQtyOrdered(),//(null!==$product->getQtyOrdered() && !empty($product->getQtyOrdered()) ? $product->getQtyOrdered() : ''),
                );
			}
        }
		*/
		$x = $order->getAllItems();
		//print_r($product);
		//print_r($itemInfo);
		
		/*
        
        $dataa = array(
            'order_id' => $order_number,
            'pw_id' => $pw_id,
            'txn_id' => $post_data['txn_id'],
            'pg_txn_id' => $post_data['pg_txn_id'],
            'amount' => $post_data['amount'],
            'tax_exempted_amount' => $post_data['tax_exempted_amount'],
            'processing_fee_amount' => $post_data['processing_fee_amount'],
            'shipping_amount' => $post_data['shipping_amount'],
            'handling_amount' => $post_data['handling_amount'],
            'tax_amount' => $post_data['tax_amount'],
            'total_amount' => $post_data['total_amount'],
            'final_amount' => $post_data['final_amount'],
            'currency_code' => $post_data['currency_code'],
            'subscription_token' => $post_data['subscription_token'],
            'charge_index' => $post_data['charge_index'],
            'payment_method_code' => $post_data['payment_method_code'],
            'payment_method_title' => $post_data['payment_method_title'],
            'reversible_payment' => $post_data['reversible_payment'],
            'settlement_account' => $post_data['settlement_account'],
            'require_capture' => $post_data['require_capture'],
            'transaction_status' => $post_data['transaction_status']
            );
            
        try {
            $NotificationInformationModel->setData($dataa)->save();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        
        $text = $NotificationInformationModel->load($order_number);
        var_dump($text);
        */
        /*
        $note = 'ginnie love';
        var_dump($note);
        $this->view->setName($note);
        $n = $this->view->getName();
        var_dump($n);
        */
        //create invoice
        /*
        if ($state=='processing') {
            try {
                if(!$order->canInvoice())
                {
                Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
                }
                 
                $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
                 
                if (!$invoice->getTotalQty()) {
                Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
                }
                 
                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
                $invoice->register();
                $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());
                 
                $transactionSave->save();
                }
                catch (Mage_Core_Exception $e) {
                 
            }
        }
        */

        /*
        try{

          $order->save();

        }

        catch(Exception $error){

          //Mage::log($error->getMessage(), null, ‘yourlogfile.log’);
          $this->information->setLog($error->getMessage());

        }
        */
        //echo 'efg';
        /*
        
        //if shipping not enabled (auto)
        //if invoice not enabled (auto)
        if (!$this->helper->isShippingEnabled()&& !$this->helper->isInvoiceEnabled()) {
            return $this;
        }
        //if invoice enabled
        if ($this->helper->isInvoiceEnabled()) {
            
            $order = $observer->getEvent()->getOrder();
            if(!$order->getId()) {
                return $this;
            }
            $invoice = $this->helper->createInvoice($order);
            
            //if invoice created
            //if shipping enabled (auto)
            if($invoice && isShippingEnabled()) {
                $this->helper->createShipment($order, $invoice);
            }

            return $this;
        }
        return $this;
        */
        
        
        //print_r($post_data['pg_raw_data']);
        //var_dump($post_data['pg_raw_data']);
        //print_r($post_data['pg_raw_data'][1]);

    }
    
}