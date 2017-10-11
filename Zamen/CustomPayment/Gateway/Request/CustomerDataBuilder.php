<?php
namespace Zamen\CustomPayment\Gateway\Request;

//refer magento
use Magento\Payment\Gateway\Request\BuilderInterface;

//our own
use Zamen\CustomPayment\Gateway\Helper\SubjectReader;

class CustomerDataBuilder implements BuilderInterface
{
    const CUSTOMER = 'customer';
    const FIRST_NAME = 'firstName';
    const LAST_NAME = 'lastName';
    const COMPANY = 'company';
    const EMAIL = 'email';
    const PHONE = 'phone';

    private $subjectReader;
    public function __construct(SubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $order = $paymentDO->getOrder();
        $billingAddress = $order->getBillingAddress();

        return
            [
                self::CUSTOMER =>
                    [
                        self::FIRST_NAME => $billingAddress->getFirstname(),
                        self::LAST_NAME => $billingAddress->getLastname(),
                        self::COMPANY => $billingAddress->getCompany(),
                        self::PHONE => $billingAddress->getTelephone(),
                        self::EMAIL => $billingAddress->getEmail(),
                    ]
            ];
    }
}