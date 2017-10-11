<?php
namespace Zamen\CustomPayment\Gateway\Request;

//refer magento
use Magento\Payment\Gateway\Request\BuilderInterface;

//our own
use Zamen\CustomPayment\Gateway\Helper\SubjectReader;

class AddressDataBuilder implements BuilderInterface
{
    //address
    const SHIPPING_ADDRESS = 'shipping';
    const BILLING_ADDRESS = 'billing';

    //name
    const FIRST_NAME ='firstName';
    const LAST_NAME = 'lastName';

    //address again?
    const COMPANY = 'company';
    const STREET_ADDRESS = 'streetAddress';//line1
    const EXTENDED_ADDRESS = 'extendedAddress';//line2
    const LOCALITY = 'locality';
    const REGION = 'region';
    const POSTAL_CODE = 'postalCode';
    const COUNTRY_CODE = 'countryCodeAlpha2';


    private $subjectReader;
    public function __construct(SubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $order = $paymentDO->getOrder();
        $result=  [];

        $billingAddress = $order->getBillingAddress();
        if ($billingAddress)
        {
            $result[self::BILLING_ADDRESS] =
                [
                    self::FIRST_NAME => $billingAddress->getFirstName(),
                    self::LAST_NAME => $billingAddress->getLastName(),
                    self::COMPANY => $billingAddress->getCompany(),
                    self::STREET_ADDRESS => $billingAddress->getStreetLine1(),
                    self::EXTENDED_ADDRESS => $billingAddress->getStreetLine2(),
                    self::LOCALITY => $billingAddress->getCity(),
                    self::REGION => $billingAddress->getRegionCode(),
                    self::POSTAL_CODE => $billingAddress->getPostCode(),
                    self::COUNTRY_CODE => $billingAddress->getCountryId(),
                ];
        }

        $shippingAddress = $order->getShippingAddress();
        if ($shippingAddress)
        {
            $result[self::SHIPPING_ADDRESS] =
                [
                    self::FIRST_NAME => $shippingAddress->getFirstName(),
                    self::LAST_NAME => $shippingAddress->getLastName(),
                    self::COMPANY => $shippingAddress->getCompany(),
                    self::STREET_ADDRESS => $shippingAddress->getStreetLine1(),
                    self::EXTENDED_ADDRESS => $shippingAddress->getStreetLine2(),
                    self::LOCALITY => $shippingAddress->getCity(),
                    self::REGION => $shippingAddress->getRegionCode(),
                    self::POSTAL_CODE => $shippingAddress->getPostCode(),
                    self::COUNTRY_CODE => $shippingAddress->getCountryId(),
                ];
        }

        return $result;
    }
}