<?php
namespace Zamen\CustomPayment\Gateway\Request;

//refer magento
use Magento\Payment\Helper\Formatter;
use Magento\Payment\Gateway\Request\BuilderInterface;

//our own
use Zamen\CustomPayment\Gateway\Config\Config;
use Zamen\CustomPayment\Observer\PaymentObserver;
use Zamen\CustomPayment\Gateway\Helper\SubjectReader;

class PaymentDataBuilder implements BuilderInterface
{
    use Formatter;

    const AMOUNT = 'amount';

    //Token provided by customer
    //may need to change it to admin api to receive token
    const PAYMENT_METHOD_NONCE = 'paymentMethodNonce';
    
    //maybe pipwave no this function?
    const MERCHANT_ACOUNT_ID = 'merchantAccountId';
    const ORDER_ID = 'orderId';

    private $config;
    private $subjectReader;

    public function __construct(Config $config, SubjectReader $subjectReader)
    {
        $this->config = $config;
        $this->subjectReader = $subjectReader;
    }

    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();

        $result =
            [
                self::AMOUNT => $this->formatPrice($this->subjectReader->readAmount($buildSubject)),
                self::PAYMENT_METHOD_NONCE => $payment->getAdditionalInformation(PaymentObserver::PAYMENT_METHOD_NONCE),
                self::ORDER_ID => $order->getOrderIncrementId(),
            ];

        $merchantAccountId = $this->config->getMerchantAccountId();
        if (!empty($merchantAccountId))
        {
            $result[self::MERCHANT_ACOUNT_ID] = $merchantAccountId;
        }
        return $result;
    }
}