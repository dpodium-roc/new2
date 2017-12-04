<?php
namespace pipwave\CustomPayment\Block;

use \Magento\Store\Model\StoreManagerInterface as storeManager;
use \Magento\Customer\Model\Session as customer;
use \Magento\Checkout\Model\Session as checkout;
use \Magento\Framework\App\ProductMetadataInterface as productMetadata;
use \pipwave\CustomPayment\Helper\Data as adminData;
use \pipwave\CustomPayment\Model\Url as urlLink;
use \pipwave\CustomPayment\Model\PipwaveIntegration as pipwaveIntegration;
use \Magento\Sales\Model\Order;

class InformationNeeded extends \Magento\Framework\View\Element\Template
{    
    public function __construct(
    \Magento\Checkout\Model\Cart $cart,
    storeManager $storeManager,
    customer $customer,
    checkout $checkout,
    adminData $adminData,
    urlLink $urlLink,
    pipwaveIntegration $pipwaveIntegration,
    \Magento\Sales\Model\Order\CreditmemoFactory $creditmemoFactory,
    \Magento\Sales\Model\Order\Invoice $Invoice,
    \Magento\Sales\Model\Service\CreditmemoService $CreditmemoService,
    productMetadata $productMetadata
    
    ) {
        $this->cart = $cart;
        $this->_storeManager = $storeManager;
        $this->customer = $customer;
        $this->checkout = $checkout;
        $this->adminConfig = $adminData;
        $this->urlLink = $urlLink;
        $this->pipwaveIntegration = $pipwaveIntegration;
        $this->creditmemoFactory = $creditmemoFactory;
        $this->CreditmemoService = $CreditmemoService;
        $this->Invoice = $Invoice;
        $this->productMetadata = $productMetadata;
    }
    
    //object/class
    protected $cart;
    protected $customer;
    protected $checkout;
    protected $order;
    protected $adminConfig;
    protected $urlLink;
    protected $pipwaveIntegration;
    protected $productMetadata;
    protected $_storeManager;
    
    protected $creditmemoFactory;
    protected $CreditmemoService;
    protected $Invoice;
    
    //variables
    protected $data;
    protected $url;
    protected $renderUrl;
    protected $loadingImageUrl;
    protected $testMode;
    
    
    
    function prepareData()
    {
        self::set_data();
        self::set_signature_param();
    }
    
    //no need now
    function get_manager()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        
        
        //$this->cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        //$this->customer = $objectManager->get('Magento\Customer\Model\Session');
        //$this->checkout = $objectManager->get('Magento\Checkout\Model\Session');
        
        //$this->order = $objectManager->get('\Magento\Sales\Model\Order');
        
        //$this->adminConfig = $objectManager->get('pipwave\CustomPayment\Helper\Data');
        //$this->urlLink = $objectManager->get('pipwave\CustomPayment\Model\Url');
        
        //$this->pipwaveIntegration = $objectManager->get('pipwave\CustomPayment\Model\PipwaveIntegration');
    }
    
    protected $notificationUrl;
    protected $failUrl;
    
    function set_data()
    {
        $amt = $this->cart->getQuote()->getGrandTotal();
        
        //$this->customer->getQuote()->reserveOrderId();
        $order = $this->checkout->getLastRealOrder();
        $orderId = $order->getIncrementId();
        $total = $order->getGrandTotal();
        //$orderId = $this->order->getIncrementId();
        $successUrl = $this->urlLink->defaultSuccessPageUrl();
        $this->failUrl = $this->urlLink->defaultFailPageUrl();
        $this->notificationUrl = $this->urlLink->notificationPageUrl();
        
        //add ngrok url to replace 'localhost'
        $this->notificationUrl = 'https://93a3b5dc.ngrok.io/magento2-develop/magento2-develop/notification/notification/index';
        
        
        //address
        /*
        $quote = $this->checkout->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $billingAddress = $quote->getBillingAddress();
        */
        //'city' =>$shippingAddress->getCity();
        //'zip' =>$shippingAddress->getPostCode();
        //'country' => $shippingAddress->getCountryId();
        //'email' =>$shippingAddress->getEmail();
        //'contact_no' =>$shippingAddress->getTelephone();
        //'address1' => $shippingAddress->getStreet();
        
        //'city' =>$billingAddress->getCity();
        //'zip' =>$billingAddress->getPostCode();
        //'country' => $billingAddress->getCountryId();
        //'email' =>$billingAddress->getEmail();
        //'contact_no' =>$billingAddress->getTelephone();
        //'address1' => $billingAddress->getStreet();
        
        //ship address
        $shipAddress1 = '';
        $shipAddress2 = '';
        $billAddress1 = '';
        $billAddress2 = '';
        
        if ($order->getShippingAddress()->getStreet()!=null) {
            $shipAddress1 = implode(' ', $order->getShippingAddress()->getStreet());
        }
        
        //shipp address2 below
        /*
        if ($order->getShippingAddress()->getStreet(1)!=null) {
            $shipAddress2 = implode(' ', $order->getShippingAddress()->getStreet(1));
        }
        */
        
        //bill address
        if ($order->getBillingAddress()->getStreet()!=null) {
            $billAddress1 = implode(' ', $order->getBillingAddress()->getStreet());
        }
        //bill address2 below
        /*
        if ($order->getBillingAddress()->getStreet(1)!=null) {
           $billAddress2 = implode(' ', $order->getBillingAddress()->getStreet(1));
        }
        */
        $this->data = array(
            'action' => 'initiate-payment',
            'timestamp' => time(),
            'api_key' => $this->adminConfig->getApiKey(),
            'api_secret' => $this->adminConfig->getApiSecret(),
            'txn_id' => $orderId,
            'amount' => round($total, 2),
            'currency_code' => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
            'shipping_amount' => $order->getShippingAmount(),
            'buyer_info' => array(
                'id' => $this->customer->getCustomerId(),
                'email' => $this->customer->getCustomer()->getEmail(),
                'first_name' => $order->getBillingAddress()->getFirstname(),
                'last_name' => $order->getBillingAddress()->getLastname(),
                'contact_no' =>$order->getBillingAddress()->getTelephone(),
                'country_code' => $order->getBillingAddress()->getCountryId(),
                'surcharge_group' => $this->adminConfig->getProcessingFee(),
            ),
            //testing below two variables
            'shipping_info' => //$this->cart->getQuote()->getShippingAddress()->getData(),
                array(
                'name' =>$order->getShippingAddress()->getFirstname().' '.$order->getShippingAddress()->getLastname(),
                'city' =>$order->getShippingAddress()->getCity(),
                'zip' =>$order->getShippingAddress()->getPostCode(),
                'country_iso2' => $order->getShippingAddress()->getCountryId(),
                'email' =>$order->getShippingAddress()->getEmail(),
                'contact_no' =>$order->getShippingAddress()->getTelephone(),
                'address1' => $shipAddress1,
                //'address2' => $shipAddress2,
                'state' => $order->getShippingAddress()->getRegion(),
                ),
            'billing_info' =>
                array(
                'name' =>$order->getBillingAddress()->getFirstname().' '.$order->getBillingAddress()->getLastname(),
                'city' =>$order->getBillingAddress()->getCity(),
                'zip' =>$order->getBillingAddress()->getPostCode(),
                'country_iso2' => $order->getBillingAddress()->getCountryId(),
                'email' =>$order->getBillingAddress()->getEmail(),
                'contact_no' =>$order->getBillingAddress()->getTelephone(),
                'address1' => $billAddress1,
                //'address2' => $billAddress2,
                'state' => $order->getBillingAddress()->getRegion(),
                ),
            'api_override' => array(
                'success_url' => $successUrl,
                'fail_url' => $this->failUrl,
                'notification_url' => $this->notificationUrl,
                ),
            
        );
        //print_r($this->data);
        /*
        foreach ($order->getAllItems() as $item) { 
            $data['item_info'][] = array(
                'name' => $item->getName(),
                'amount' => $item->getPrice(),
                'quantity' => $item->getQtyOrdered(),
                'sku' => $item->getSku(),
            );
        }
        */
        
        $itemInfo = array();
        foreach ($order->getAllItems() as $item) {
            $product = $item->getProduct();
            
            // some weird things came out if no if else
            if ((float)$product->getPrice()!=0) {
            $itemInfo[] = array(
                'name' => (null!==$product->getName() && !empty($product->getName()) ? $product->getName() : ''),
                'sku' => (null!==$product->getSku() && !empty($product->getSku()) ? $product->getSku() : ''),
                'currency_code' => $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
                'amount' => (float)$product->getPrice(),//(null!==$product->getPrice() && !empty($product->getPrice()) ? $product->getPrice() : ''),
                'quantity' => (int)$item->getQtyOrdered(),//(null!==$product->getQtyOrdered() && !empty($product->getQtyOrdered()) ? $product->getQtyOrdered() : ''),
                );
            }
        }
        if (count($itemInfo) > 0) {
            $this->data['item_info'] = $itemInfo;
        }
        //print_r($this->data['shipping_info']);
        
        $this->testMode = $this->adminConfig->getTestMode();
        $this->url = $this->urlLink->getUrl($this->testMode);
        $this->renderUrl = $this->urlLink->getRenderUrl($this->testMode);
        $this->loadingImageUrl = $this->urlLink->getLoadingImageUrl($this->testMode);
    }
    
    function set_addr() {
        
        $this->data['shipping_info'] = '';
    }

    protected $signatureParam;
    function set_signature_param()
    {
        //need modification, call object manager?
        //read some_functions_get_information.php [deskstop]
        $this->signatureParam = array(
            'api_key' => $this->data['api_key'],
            'api_secret' => $this->data['api_secret'],
            'txn_id' => $this->data['txn_id'],
            'amount' => $this->data['amount'],
            'currency_code' => $this->data['currency_code'],
            'action' => $this->data['action'],
            'timestamp' => $this->data['timestamp']
        );
    }
    
    // insert signature into data['signature']
    function insert_signature()
    {
        $this->data['signature'] = $this->pipwaveIntegration->generate_pw_signature($this->signatureParam);
    }
    
    protected $response;
    function sendRequest()
    {
        $agent = $this->pipwaveIntegration->get_agent();
        $this->response = $this->pipwaveIntegration->send_request_to_pw($this->data, $this->data['api_key'], $this->url, $agent);
    }
    
    protected $result;
    function render()
    {
        $this->pipwaveIntegration->render_sdk($this->response, $this->data['api_key'], $this->renderUrl, $this->loadingImageUrl);
    }


    function get_data()
    {
        return $this->data;
    }
    function get_signature_param()
    {
        return $this->signatureParam;
    }
    function get_url()
    {
        return $this->url;
    }
    function get_render_url()
    {
        return $this->renderUrl;
    }
    function get_loading_img_url()
    {
        return $this->loadingImageUrl;
    }
    function get_response()
    {
        return $this->response;
    }
    
    function get_result()
    {
        return $this->pipwaveIntegration->get_result();
    }
    
    function getOrderId()
    {
        return $this->data['txn_id'];
    }

    
    protected $version;
    function get_version()
    {
        $this->version = $this->productMetadata->getVersion();
        return $this->version;
    }
    
    function getNotificationUrl()
    {
        return $this->notificationUrl;
    }
    
    //order status 
    //pipwave status and magento status
    const PIPWAVE_PENDING = \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT;
    const PIPWAVE_FAIL = \Magento\Sales\Model\Order::STATE_CANCELED;
    const PIPWAVE_CANCELED = \Magento\Sales\Model\Order::STATE_CANCELED;
    const PIPWAVE_PAID = \Magento\Sales\Model\Order::STATE_PROCESSING;
    const PIPWAVE_FULL_REFUNDED = \Magento\Sales\Model\Order::STATE_CLOSED;
    const PIPWAVE_PARTIAL_REFUNDED = \Magento\Sales\Model\Order::STATE_PROCESSING;
    const PIPWAVE_SIGNATURE_MISMATCH = \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT;
    const PIPWAVE_UNKNOWN_STATUS = \Magento\Sales\Model\Order::STATE_PENDING_PAYMENT;
    
    protected $paid = false;
    function processNotification($transaction_status, $order, $refund_amount, $txn_sub_status)
    {
        switch ($transaction_status) {
                case 5: // pending
                    $status = SELF::PIPWAVE_PENDING;
                    $order->setState($status)->setStatus($status);
                    $order->addStatusHistoryComment('Payment status: Pending payment.')->setIsCustomerNotified(true);
                    break;
                case 1: // failed
                    $status = SELF::PIPWAVE_FAIL;
                    $order->setState($status)->setStatus($status);
                    $order->cancel();
                    $order->addStatusHistoryComment('Payment status: Failed.')->setIsCustomerNotified(true);
                    break;
                case 2: // cancelled
                    $status = SELF::PIPWAVE_CANCELED;
                    $order->setState($status)->setStatus($status);
                    $order->addStatusHistoryComment('Payment status: Canceled.')->setIsCustomerNotified(true);
                    //$status = $method->status_cancelled;
                    break;
                case 10: // complete
                    //$status = SELF::PIPWAVE_PAID;
                    //$order->setState($status)->setStatus($status);
                    
                    //502
                    if ($txn_sub_status==502) {
                        $order->addStatusHistoryComment('Payment status: Paid.')->setIsCustomerNotified(true);
                        //if auto (invoice-shipping) enabled
                        if ($this->adminConfig->isShippingEnabled()==1 || $this->adminConfig->isInvoiceEnabled()==1 ) {

                            //if auto-invoice enabled
                            if ($this->adminConfig->isInvoiceEnabled()==1) {
                                //create invoice
                                $invoice = $this->adminConfig->createInvoice($order);
                                //var_dump($invoice);
                                $order->addStatusHistoryComment('Invoice created automatically', false);
                                $order->addStatusHistoryComment(__('Notified customer about invoice #%1.', $invoice['id']))->setIsCustomerNotified(true);
                                if($invoice && $this->adminConfig->isShippingEnabled()==1) {
                                    //create shipment
                                    $this->adminConfig->createShipment($order,$invoice);
                                    $order->addStatusHistoryComment('Shipment created automatically', false);
                                }
                            }
                        }
                    }
                    break;
                case 20: // refunded
                    $status = SELF::PIPWAVE_FULL_REFUNDED;
                    $order->setState($status)->setStatus($status);
                    
                    $invoices = $order->getInvoiceCollection();
                    foreach($invoices as $invoice){
                        $invoiceincrementid = $invoice->getIncrementId();
                    }
                    //var_dump($order);
                    //
                    $invoiceObj =  $this->Invoice->loadByIncrementId($invoiceincrementid);
                    $creditMemo = $this->creditmemoFactory->createByOrder($order);
                    //var_dump($creditMemo);
                    $creditMemo->setInvoice($invoiceObj);
                    $this->CreditmemoService->refund($creditMemo);
                    
                    $order->addStatusHistoryComment('Payment status: Full refunded.')->setIsCustomerNotified(true);
                    break;
                case 25: // partial refunded
                    $status = SELF::PIPWAVE_PARTIAL_REFUNDED;
                    $order->setState($status)->setStatus($status);
                    
                    $order->addStatusHistoryComment('Payment status: Partial Refunded. Amount: '.$refund_amount)->setIsCustomerNotified(true);
                    break;
                case -1: // signature mismatch
                    $status = SELF::PIPWAVE_SIGNATURE_MISMATCH;
                    $order->setState($status)->setStatus($status);
                    $order->addStatusHistoryComment('Payment status: Signature Mismatch.')->setIsCustomerNotified(true);
                    break;
                default:
                    $status = SELF::PIPWAVE_UNKNOWN_STATUS;
                    $order->setState($status)->setStatus($status);
            }
        return $order;
    }
    function isPaid()
    {
        return $this->paid;
    }
    


    function createInvoice($order)
    {
        $result = $this->adminConfig->createInvoice($order);
        return $result;
    }
    function createShipment($order,$invoice)
    {
        $this->adminConfig->createShipment($order,$invoice);
    }
    function prepareShipment($invoice)
    {
        $result = $this->adminConfig->prepareShipment($invoice);
        return $result;
    }
    function isShippingEnabled()
    {
        return $this->adminConfig->isShippingEnabled();
    }
    function isInvoiceEnabled()
    {
        $result = $this->adminConfig->isInvoiceEnabled();
        return $result;
    }
    
    function getTestMode()
    {
        return $this->adminConfig->getTestMode();
    }
}