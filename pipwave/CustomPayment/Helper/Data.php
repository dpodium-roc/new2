<?php
namespace pipwave\CustomPayment\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Sales\Model\Order\ShipmentFactory $shipmentFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->invoiceService = $invoiceService;
        $this->shipmentFactory = $shipmentFactory;
        $this->transactionFactory = $transactionFactory;
    }
    
    
    protected $scopeConfig;
    //from etc\adminhtml\system.xml
    const API_KEY = 'payment/custompayment/api_key';
    const API_SECRET = 'payment/custompayment/api_secret';
    const TEST_MODE = 'payment/custompayment/test_mode';
    const PROCESSING_FEE = 'payment/custompayment/processing_fee';
    const AUTO_SHIPPING = 'payment/custompayment/auto_shipping';
    const AUTO_INVOICE = 'payment/custompayment/auto_invoice';
    
    public function getApiKey()
    {
        return $this->scopeConfig->getValue(
            self::API_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getApiSecret()
    {
        return $this->scopeConfig->getValue(
            self::API_SECRET,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getTestMode()
    {
        return $this->scopeConfig->getValue(
            self::TEST_MODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getProcessingFee()
    {
        return $this->scopeConfig->getValue(
            self::PROCESSING_FEE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function isShippingEnabled()
    {
        return $this->scopeConfig->getValue(
            self::AUTO_SHIPPING,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function isInvoiceEnabled()
    {
        return $this->scopeConfig->getValue(
            self::AUTO_INVOICE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    
    //method for auto-(shipping/invoice)
    protected $invoiceCollectionFactory;
    protected $invoiceService;
    protected $shipmentFactory;
    protected $transactionFactory;
    
    //auto create invoice
    public function createInvoice($order)
    {
        try {
            $invoices = $this->invoiceCollectionFactory->create()
                ->addAttributeToFilter('order_id', array('eq' => $order->getId()));


            //$invoices->getSelect()->limit(1);
            /*
            if ((int)$invoices->count() !== 0) {
                return null;
            }

            if(!$order->canInvoice()) {
                return null;
            }
            */
            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $invoice->getOrder()->setCustomerNoteNotify(true);
            $invoice->getOrder()->setIsInProcess(true);
            $transactionSave = $this->transactionFactory->create()->addObject($invoice)->addObject($invoice->getOrder());
            $transactionSave->save();
            
        } catch (\Exception $e) {
            $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), false);
            $order->save();
            return null;
        }

        return $invoice;
    }
    
    //auto shipping
    public function createShipment($order, $invoice)
    {
        try {
            $shipment = $this->prepareShipment($invoice);
            if ($shipment) {
                $order->setIsInProcess(true);
                
                $this->transactionFactory->create()->addObject($shipment)->addObject($shipment->getOrder())->save();
            }
        } catch (\Exception $e) {
            $order->addStatusHistoryComment('Exception message: '.$e->getMessage(), true);
            $order->save();
        }
    }

    public function prepareShipment($invoice)
    {
        $shipment = $this->shipmentFactory->create(
            $invoice->getOrder(),
            []
        );

        return $shipment->getTotalQty() ? $shipment->register() : false;
    }

    
    
    
}